<?php

namespace App\Listeners;

use App\Events\BooksFetchContentEvent;
use App\Jobs\BooksContentMultiJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksModel;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;

class BooksFetchContentListener
{
    /**
     * 处理事件
     *
     * @param BooksFetchContentEvent $event
     */
    public function handle(BooksFetchContentEvent $event)
    {
        $bookId = $event->bookId;
        $bookQuery = BooksModel::query()
            ->where(['status' => BooksModel::ENABLE_STATUS, 'update_status' => BooksModel::UPT_STATUS_LOADING]);
        if (!empty($bookId)) {
            $bookQuery->where('books_id', $bookId);
        }
        /**
         * @var BooksModel[] $bookArr
         */
        $bookArr = $bookQuery->get();
        foreach ($bookArr as $book) {
            $chapterUrlArr = BooksChapterModel::query()->select('from_url')
                ->where('books_id', $book->id)
                ->where('is_success', BooksChapterModel::ENABLE_STATUS)
                ->pluck('from_url')->toArray();
            $rule = CollectionRuleModel::query()->where('id', $book->rule_id)->first();
            /**
             * @var BookRule $bookRule
             */
            $bookRule = unserialize($rule->rule_json);
            $group = array_chunk($chapterUrlArr, 200);
            foreach ($group as $_urls) {
                dispatch(new BooksContentMultiJob($bookRule, $_urls))->onQueue('FixContent');
            }
        }
    }
}
