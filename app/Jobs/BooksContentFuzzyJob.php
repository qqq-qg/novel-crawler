<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Repositories\Searcher\Plugin\CurlMulti;
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
                    $qlUrl = $r['info']['url'];
                    $urlHash = md5(trim($qlUrl));
                    $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
                    $content = (new TryAnalysisContent('', $ql))->handle();
                    if (empty($content)) {
                        return false;
                    }
                    if (!empty($content)) {
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
}
