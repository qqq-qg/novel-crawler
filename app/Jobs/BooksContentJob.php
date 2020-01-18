<?php

namespace App\Jobs;

use App\Models\Books\BooksContentModel;
use App\Repositories\CollectionRule\BookRule;
use QL\QueryList;

class BooksContentJob extends BaseJob
{
    private $chapterModel, $bookRule;

    /**
     * BooksJob constructor.
     * @param $chapterModel
     * @param BookRule $bookRule
     */
    public function __construct($chapterModel, BookRule $bookRule)
    {
        parent::__construct();
        $this->chapterModel = $chapterModel;
        $this->bookRule = $bookRule;
    }

    public function handle()
    {
        $data = QueryList::get($this->chapterModel->from_url)
            ->range($this->bookRule->content->range)
            ->rules($this->bookRule->content->rules)
            ->query()->getData()->first();
        $content = trim($data['content'] ?? '');
        if (!empty($data) && !empty($content)) {
            $contentModel = BooksContentModel::query()->where('id', $this->chapterModel->id)->first();
            if (!empty($contentModel)) {
                $contentModel->update(['content' => $content,]);
            } else {
                BooksContentModel::query()->create(['id' => $this->chapterModel->id, 'content' => $content,]);
            }
        }
    }
}
