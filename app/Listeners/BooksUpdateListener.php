<?php

namespace App\Listeners;

use App\Events\BooksUpdateEvent;
use App\Jobs\UpdateBooksFuzzyJob;
use App\Jobs\UpdateBooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;

class BooksUpdateListener
{
  /**
   * 处理事件
   *
   * @param BooksUpdateEvent $event
   * @return bool
   */
  public function handle(BooksUpdateEvent $event)
  {
    $book = $event->book;
    if (empty($book->rule_id)) {
      dispatch(new UpdateBooksFuzzyJob($book));
      return true;
    }
    $rule = CollectionRuleModel::query()->where('id', $book->rule_id)->first();
    /**
     * @var BookRule $bookRule
     */
    $bookRule = unserialize($rule->rule_json);
    dispatch(new UpdateBooksJob($bookRule, $book));
    return true;
  }
}
