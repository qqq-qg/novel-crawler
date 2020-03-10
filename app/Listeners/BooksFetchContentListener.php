<?php

namespace App\Listeners;

use App\Events\BooksFetchContentEvent;
use App\Jobs\BooksContentFuzzyJob;
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
        if (is_array($bookId) && !empty($bookId)) {
            $bookQuery->whereIn('id', $bookId);
        }
        /**
         * @var BooksModel[] $bookArr
         */
        $bookArr = $bookQuery->get();
        foreach ($bookArr as $book) {
            $chapterUrlArr = BooksChapterModel::query()->select('from_url')
                ->where('books_id', $book->id)
                ->where('fetch_times', '<', BooksChapterModel::MAX_FETCH_TIMES)
                ->whereIn('is_success', [BooksChapterModel::DEFAULT_STATUS, BooksChapterModel::DISABLE_STATUS])
                ->pluck('from_url')->toArray();
            if (empty($chapterUrlArr)) {
                continue;
            }
            if (empty($book->rule_id)) {
                foreach (array_chunk($chapterUrlArr, 200) as $_urls) {
                    dispatch(new BooksContentFuzzyJob($_urls));
                }
                continue;
            }
            $rule = CollectionRuleModel::query()->where('id', $book->rule_id)->first();
            /**
             * @var BookRule $bookRule
             */
            $bookRule = unserialize($rule->rule_json);
            $group = array_chunk($chapterUrlArr, BooksChapterModel::CHUNK_COUNT);
            foreach ($group as $_urls) {
                dispatch(new BooksContentMultiJob($bookRule, $_urls));
            }
        }
    }
}
