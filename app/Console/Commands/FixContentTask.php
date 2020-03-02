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
        $ids = $this->getBooksIds();
        /**
         * @var BooksModel[] $booksModelArr
         */
        $booksModelArr = BooksModel::query()->whereIn('id', $ids)->get();
        foreach ($booksModelArr as $book) {
            $urls = BooksChapterModel::query()
                ->where('books_id', $book->id)
                ->where('is_success', 0)
                ->orderBy('id', 'asc')
                ->pluck('from_url')->toArray();
            if (!empty($book->rule_id)) {
                $rule = CollectionRuleModel::getRuleById($book->rule_id);
                /**
                 * @var BookRule $bookRule
                 */
                $bookRule = unserialize($rule->rule_json);
                foreach (array_chunk($urls, 200) as $_urls) {
                    dispatch(new BooksContentMultiJob($bookRule, $_urls, false));
                }
                continue;
            }
            foreach (array_chunk($urls, 200) as $_urls) {
                dispatch(new BooksContentFuzzyJob($_urls));
            }
        }
        return true;
    }

    private function getBooksIds()
    {
        $arg = $this->argument('id');
        $res = preg_split("/[,，]+/u", $arg);
        $arr = [];
        foreach ($res as $v) {
            if (strpos($v, '-')) {
                $tmp = explode('-', $v);
                for ($i = $tmp[0]; $i <= $tmp[1]; $i++) {
                    $arr[] = intval($i);
                }
            } else {
                $arr[] = intval($v);
            }
        }
        sort($arr);
        return array_unique($arr);
    }
}
