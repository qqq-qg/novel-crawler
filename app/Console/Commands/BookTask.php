<?php

namespace App\Console\Commands;

use App\Jobs\BooksJob;
use App\Models\Books\CollectionTaskModel;
use Illuminate\Console\Command;
use QL\QueryList;

class BookTask extends Command {
  protected $signature = 'task:run {--id=}';

  protected $description = '测试';

  public function handle() {
    $id = $this->option('id');
    $query = CollectionTaskModel::query()
      ->with('rule')
      ->where('status', CollectionTaskModel::ENABLE_STATUS);
    if (!empty($id)) {
      $query->where('id', $id);
    }
    $tasks = $query->get();
    foreach ($tasks as $task) {
      $ruleConfig = json_decode($task->rule['rule_json'] ?? '', true);
      if (empty($task['from_url']) || empty($ruleConfig)) {
        continue;
      }
      echo "开始执行 ==> {$task['from_url']} --page_limit={$task['page_limit']}" . PHP_EOL;
      for ($i = 1; $i <= $task['page_limit']; $i++) {
        $url = str_replace('{$page}', $i, $task['from_url']);
        echo "\tGET {$url}" . PHP_EOL;
        $this->queryData($url, $ruleConfig);
      }
    }
  }

  private function queryData($url, $config) {
    $data = QueryList::get($url)
      ->range($config['category']['range'])
      ->rules($config['category']['rules'])
      ->query()->getData();
    $homeUrlArr = $data->pluck('url')->all();
    foreach ($homeUrlArr as $homeUrl) {
      dispatch(new BooksJob($homeUrl, $config));
    }
  }
}
