<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\CurlMulti;
use App\Repositories\Searcher\Plugin\FilterHeader;
use QL\QueryList;

/**
 * 批量采集章节正文
 * Class BooksContentMultiJob
 * @Date: 2020/01/20 16:54
 * @package App\Jobs
 */
class BooksContentMultiJob extends BaseJob
{
    private $urls, $bookRule;

    /**
     * BooksJob constructor.
     * @param array $urls
     * @param BookRule $bookRule
     */
    public function __construct(BookRule $bookRule, array $urls)
    {
        parent::__construct();
        $this->urls = $urls;
        $this->bookRule = $bookRule;
        $this->queue = 'Content';
    }

    public function handle()
    {
        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti($this->urls, ['verify' => false])
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                $urlHash = md5(trim($r['info']['url']));
                $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
                if ($this->bookRule->needEncoding()) {
                    $ql->use(FilterHeader::class)->filterHeader();
                }
                $data = $ql
                    ->range($this->bookRule->content->range)
                    ->rules($this->bookRule->content->rules)
                    ->query()->getData()->first();

                $content = trim($data['content'] ?? '');
                if (!empty($this->bookRule->splitTag) && strpos($content, $this->bookRule->splitTag) > -1) {
                    $content = explode($this->bookRule->splitTag, $content)[0];
                }
                foreach ($this->bookRule->replaceTags ?? [] as $tag) {
                    $content = preg_replace($tag[0], $tag[1] ?? '', $content);
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
            })
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Error url:{$errorInfo['info']['url']} \r\n";
                print_r($errorInfo['error']);
            })
            ->start([
                'maxThread' => 10,
                'maxTry' => 1,
            ]);
    }
}
