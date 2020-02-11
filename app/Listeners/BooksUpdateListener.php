<?php

namespace App\Listeners;

use App\Events\BooksUpdateEvent;
use App\Jobs\UpdateBooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;

class BooksUpdateListener
{
    /**
     * 处理事件
     *
     * @param BooksUpdateEvent $event
     */
    public function handle(BooksUpdateEvent $event)
    {
        $book = $event->book;
        $rule = CollectionRuleModel::query()->where('id', $book->rule_id)->first();
        /**
         * @var BookRule $bookRule
         */
        $bookRule = unserialize($rule->rule_json);
        dispatch(new UpdateBooksJob($bookRule, $book));
    }
}
