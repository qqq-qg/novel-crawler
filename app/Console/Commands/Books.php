<?php

namespace App\Console\Commands;

use App\Jobs\BooksJob;
use Illuminate\Console\Command;

class Books extends Command {
  protected $signature = 'book:init';

  protected $description = '测试';

  public function handle() {
    $config = config('book.zhengheng');
    $url = $config['baseUrl'] . '/book/912604.html';
    echo "开始执行 ==> {$url}" . PHP_EOL;
    dispatch(new BooksJob($url, $config));
  }
}
