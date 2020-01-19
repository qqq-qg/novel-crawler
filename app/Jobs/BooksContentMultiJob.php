<?php

namespace App\Jobs;

use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Repositories\CollectionRule\BookRule;
use QL\Ext\CurlMulti;
use QL\QueryList;

class BooksContentMultiJob extends BaseJob
{
    private $urls, $bookRule;

    /**
     * BooksJob constructor.
     * @param array $urls
     * @param BookRule $bookRule
     */
    public function __construct(array $urls, BookRule $bookRule)
    {
        parent::__construct();
        $this->urls = $urls;
        $this->bookRule = $bookRule;
        $this->queue = 'Content';
    }

    public function handle()
    {
        $ql = QueryList::use(CurlMulti::class);
        $ql->curlMulti($this->urls)
            ->success(function (QueryList $ql, CurlMulti $curl, $r) {
                echo "success return url:{$r['info']['url']}" . PHP_EOL;
                $urlHash = md5(trim($r['info']['url']));
                $chapterModel = BooksChapterModel::query()->where('from_hash', $urlHash)->first();
                $data = $ql
                    ->range($this->bookRule->content->range)
                    ->rules($this->bookRule->content->rules)
                    ->query()->getData()->first();
                $content = trim($data['content'] ?? '');
                if (!empty($content)) {
                    $contentModel = BooksContentModel::query()->where('id', $chapterModel->id)->first();
                    if (!empty($contentModel)) {
                        $contentModel->update(['content' => $content]);
                    } else {
                        BooksContentModel::query()->create(['id' => $chapterModel->id, 'content' => $content]);
                    }
                }
            })
            ->error(function ($errorInfo, CurlMulti $curl) {
                echo "Current url:{$errorInfo['info']['url']} \r\n";
                print_r($errorInfo['error']);
            })
            ->start([
                'maxThread' => 10,
                'maxTry' => 3,
            ]);
    }
}
