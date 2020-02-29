<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentFilterRuleModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\BooksModel;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\CurlMulti;
use App\Repositories\Searcher\Plugin\FilterHeader;
use App\Repositories\TryAnalysis\TryAnalysisContent;
use QL\QueryList;

/**
 * 批量采集章节正文
 * Class BooksContentFuzzyJob
 * @author Nacrane
 * @package App\Jobs
 */
class BooksContentFuzzyJob extends BaseJob
{
    private $urls;
    private $tryAgain = true;

    /** @var BooksContentFilterRuleModel|null $filterRuleModel */
    private $filterRuleModel = null;
    /** @var BooksModel|null $booksModel */
    private $booksModel = null;
    /** @var CollectionRuleModel|null $collectionRuleModel */
    private $collectionRuleModel = null;
    private $ruleReplaceTags = null;

    public function __construct(array $urls, $tryAgain = true)
    {
        parent::__construct();
        $this->urls = $urls;
        $this->tryAgain = $tryAgain;
        $this->queue = 'Content';
    }

    public function handle()
    {
        $againUrl = [];
        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti($this->urls)
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                try {
                    $ql->use(FilterHeader::class)->filterHeader();
                    $ql->encoding(BookRule::CHARSET_UTF8);
                    $qlUrl = $r['info']['url'];
                    $urlHash = md5(trim($qlUrl));
                    /* @var BooksChapterModel $chapterModel */
                    $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
                    $content = (new TryAnalysisContent('', $ql))->handle();
                    if (empty($content)) {
                        return false;
                    }
                    if (!empty($content)) {
                        $content = $this->analysisContentFilterRule($chapterModel, $content);
                        $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
                        if (!empty($contentModel)) {
                            $contentModel->update(['content' => $content]);
                        } else {
                            BooksContentModel::query()->create(['id' => $chapterModel->id, 'content' => $content]);
                        }
                        $chapterModel->saveProcessed();
                    }
                } catch (\Exception $e) {
                    $againUrl[] = $qlUrl;
                }
                return true;
            })
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Error url:{$errorInfo['info']['url']} \r\n";
                print_r($errorInfo['error']);
            })
            ->start([
                'maxThread' => 30,
                'maxTry' => 2,
            ]);
        if (!empty($againUrl)) {
            return $this->handelAgain();
        }
        return true;
    }

    private function handelAgain()
    {
        if ($this->tryAgain) {
            echo 'Try without rule, pls wait ...' . PHP_EOL;
            $this->tryAgain = false;
            return $this->handle();
        }
        return false;
    }

    public function analysisContentFilterRule(BooksChapterModel $chapterModel, $content)
    {
        if (is_null($this->booksModel)) {
            $this->booksModel = BooksModel::query()->where('id', $chapterModel->books_id)->first();
        }
        if (is_null($this->collectionRuleModel)) {
            if (!empty($this->booksModel->rule_id)) {
                $collectionRule = CollectionRuleModel::getRuleById($this->booksModel->rule_id) ?? null;
                if (!is_null($collectionRule)) {
                    $this->collectionRuleModel = $collectionRule;
                }
            } else {
                $urlInfo = parse_url($chapterModel->from_url);
                $collectionRule = CollectionRuleModel::getRuleByHost($urlInfo['host'] ?? '');
                if (!empty($collectionRule)) {
                    $this->booksModel->update(['rule_id' => $collectionRule->id]);
                    $this->collectionRuleModel = $collectionRule;
                }
            }
        }
        if (!is_null($this->collectionRuleModel)) {
            /** @var BookRule $bookRule */
            $bookRule = unserialize($this->collectionRuleModel->rule_json);
            return $this->replaceContentData($bookRule->replaceTags, $content);
        }

        if (is_null($this->filterRuleModel)) {
            $filterRuleModel = BooksContentFilterRuleModel::query()->where('books_id', $chapterModel->books_id)->first();
            if (empty($filterRuleModel)) {
                $filterRuleModel = new BooksContentFilterRuleModel();
                $filterRuleModel->rule = json_encode([]);
                $filterRuleModel->save();
            }
            $this->filterRuleModel = $filterRuleModel;
        }
        if (is_null($this->ruleReplaceTags)) {
            $this->ruleReplaceTags = CollectionRuleModel::getAllRuleReplaceTags();
        }
        $tags = json_decode($filterRuleModel->rule, true) ?? [];
        foreach ($this->ruleReplaceTags as $tag) {
            if (preg_match($tag[0], $content, $arr)) {
                $content = preg_replace($tag[0], $tag[1] ?? '', $content);
                if (in_array($tag[0], array_column($tags, 0))) {
                    $tags[] = $tag;
                }
            }
        }
        if (!empty($tags)) {
            $filterRuleModel->rule = json_encode($tags);
            $filterRuleModel->save();
        }
        return $content;
    }

    private function replaceContentData($replaceRules, $content)
    {
        foreach ($replaceRules as $tag) {
            $content = preg_replace($tag[0], $tag[1] ?? '', $content);
        }
        return $content;
    }
}
