<?php

namespace App\Console\Commands;

use App\Events\BooksFetchContentEvent;
use App\Models\Books\BooksModel;
use App\Repositories\Searcher\ChromeSearcherRepository;
use Illuminate\Console\Command;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class Test extends Command
{
  protected $signature = 'test';

  protected $description = '';

  public function handle()
  {
    $repo = new ChromeSearcherRepository(1);
    $data = $repo->search('行走于神话的巫');
    dd($data);
//        $redis = app("redis.connection");
//        $val = $redis->get("name");
//        dd($val);
//        $booksArr = BooksModel::query()
//            ->where(['status' => BooksModel::ENABLE_STATUS, 'update_status' => BooksModel::UPT_STATUS_LOADING])
//            ->get();
//        foreach ($booksArr as $book) {
//            event(new BooksFetchContentEvent($book->id));
//        }
//        die;

  }

  private function Puppeteer()
  {
    $puppeteer = new Puppeteer();
    $browser = $puppeteer->launch(['headless' => false]);

    $page = $browser->newPage();
    $page->goto('https://www.baidu.com');
    // Get the "viewport" of the page, as reported by the page.
    $dimensions = $page->evaluate(JsFunction::createWithBody("
    return {
        width: document.documentElement.clientWidth,
        height: document.documentElement.clientHeight,
        deviceScaleFactor: window.devicePixelRatio
    };
"));

    printf('Dimensions: %s', print_r($dimensions, true));

    $browser->close();
  }
}
