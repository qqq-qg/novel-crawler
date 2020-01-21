<?php

namespace App\Jobs;

use App\Models\Books\BooksContentModel;
use App\Repositories\CollectionRule\BookRule;
use QL\QueryList;

/**
 * 单个章节采集队列
 * Class BooksContentJob
 * @Date: 2020/01/20 16:54
 * @package App\Jobs
 */
class BooksContentJob extends BaseJob
{
    private $bookRule, $chapterId, $chapterUrl;

    /**
     * BooksContentJob constructor.
     * @param BookRule $bookRule
     * @param $chapterId
     * @param $chapterUrl
     */
    public function __construct(BookRule $bookRule, $chapterId, $chapterUrl)
    {
        parent::__construct();
        $this->bookRule = $bookRule;
        $this->chapterId = $chapterId;
        $this->chapterUrl = $chapterUrl;
        $this->queue = 'Content';
    }

    public function handle()
    {
        $data = QueryList::get($this->chapterUrl)
            ->range($this->bookRule->content->range)
            ->rules($this->bookRule->content->rules)
            ->query()->getData()->first();
        $content = trim($data['content'] ?? '');
        if (!empty($data) && !empty($content)) {
            $contentModel = BooksContentModel::query()->where('id', $this->chapterId)->first();
            if (!empty($contentModel)) {
                $contentModel->update(['content' => $content]);
            } else {
                BooksContentModel::query()->create(['id' => $this->chapterId, 'content' => $content]);
            }
        }
    }
}
