<?php

namespace App\Listeners;

use App\Events\BooksChangeSourceEvent;
use App\Jobs\NewBooksFuzzyJob;
use App\Jobs\NewBooksJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;

class BooksChangeSourceListener
{
    /**
     * 处理事件
     *
     * @param BooksChangeSourceEvent $event
     * @author Nacrane
     * @Date: 2020/02/11
     * @Time: 11:08
     */
    public function handle(BooksChangeSourceEvent $event)
    {
        $book = $event->book;

        //clear old chapter,content
        $this->clearBeforeChapter($book->id);

        if (empty($book->rule_id)) {
            dispatch(new NewBooksFuzzyJob($book->title, $book->from_url));
            return;
        }
        //add new data
        $rule = CollectionRuleModel::query()->where('id', $book->rule_id)->first();
        /**
         * @var BookRule $bookRule
         */
        $bookRule = unserialize($rule->rule_json);
        dispatch(new NewBooksJob($bookRule, $book->from_url, $book->rule_id));
    }

    private function clearBeforeChapter($booksId)
    {
        $chapterId = BooksChapterModel::query()->where('books_id', $booksId)->pluck('id')->toArray();

        $rst = BooksContentModel::query()->whereIn('id', $chapterId)->delete();
        $rst = BooksChapterModel::query()->whereIn('id', $chapterId)->delete();
    }
}
