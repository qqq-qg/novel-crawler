<?php

namespace App\Console\Commands;

use App\Jobs\BooksJob;
use App\Models\Books\CollectionTaskModel;
use App\Repositories\CollectionRule\BookRule;
use Illuminate\Console\Command;
use QL\QueryList;

class BookTask extends Command
{
    protected $signature = 'task:run {--id=}';

    protected $description = '测试';

    /**
     * @var BookRule $bookRule
     */
    public function handle()
    {
        $id = $this->option('id');
        $tasks = (new CollectionTaskModel())->getTasks($id);
        foreach ($tasks as $task) {
            if (empty($task['from_url'])) {
                continue;
            }
            $bookRule = unserialize($task->rule['rule_json']);
            echo "开始执行 ==> {$task['from_url']} -- page_limit = {$task['page_limit']}" . PHP_EOL;
            for ($i = 1; $i <= $task['page_limit']; $i++) {
                $url = str_replace('{$page}', $i, $task['from_url']);
                echo "\tGET {$url}" . PHP_EOL;
                $this->queryData($url, $bookRule);
            }
        }
    }

    private function queryData($url, BookRule $bookRule)
    {
        foreach ($bookRule->bookList as $listRlRule) {
            $data = QueryList::get($url)
                ->range($listRlRule->range)
                ->rules($listRlRule->rules)
                ->query()->getData();
            if (empty($data)) {
                continue;
            }
            $homeUrlArr = $data->pluck('url')->all();
            foreach ($homeUrlArr as $homeUrl) {
                dispatch(new BooksJob($homeUrl, $bookRule));
            }
        }

    }
}
