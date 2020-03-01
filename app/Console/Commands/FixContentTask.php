<?php

namespace App\Console\Commands;

use App\Jobs\BooksContentFuzzyJob;
use App\Jobs\BooksContentMultiJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksModel;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use Illuminate\Console\Command;

class FixContentTask extends Command
{
    protected $signature = 'fix {id}';

    protected $description = '搜索任务，启动队列';

    public function handle()
    {
        $id = $this->argument('id');
        $book = BooksModel::getBookById($id);
        if (empty($book)) {
            echo "BookId {$id} 不存在\n";
            return false;
        }
        $urls = BooksChapterModel::query()
            ->where('books_id', $book->id)
            ->where('is_success', 0)
            ->orderBy('id', 'asc')
            ->pluck('from_url')->toArray();
        if (empty($book->rule_id)) {
            /**
             * @var CollectionRuleModel $rule
             */
            $rule = CollectionRuleModel::query()->where('id', $book->rule_id)->first();
            /**
             * @var BookRule $bookRule
             */
            $bookRule = unserialize($rule->rule_json);
            foreach (array_chunk($urls, 200) as $_urls) {
                dispatch(new BooksContentMultiJob($bookRule, $_urls, false));
            }
            return true;
        }

        foreach (array_chunk($urls, 200) as $_urls) {
            dispatch(new BooksContentFuzzyJob($_urls));
        }
        return true;

    }
}
