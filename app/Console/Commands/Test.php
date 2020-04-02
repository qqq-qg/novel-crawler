<?php

namespace App\Console\Commands;

use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;
use App\Repositories\Searcher\Plugin\FilterHeader;
use Illuminate\Console\Command;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use QL\QueryList;

class Test extends Command
{
  protected $signature = 'test';

  protected $description = '';

  public function handle()
  {
    $rule = CollectionRuleModel::query()->where('id', 1)->first();
    /**
     * @var BookRule $bookRule
     */
    $bookRule = unserialize($rule->rule_json);
    $url = 'http://book.zongheng.com/store/c3/c1031/b0/u0/p1/v0/s9/t0/u0/i0/ALL.html';
    $ql = QueryList::get($url);
    if ($bookRule->needEncoding()) {
      $ql->use(FilterHeader::class)->filterHeader();
      $ql->encoding(BookRule::CHARSET_UTF8);
    }
    $bookRule->bookList['category']->rules = [
      'url'=>['span.bookname>a','href']
    ];
    $bookRule->bookList['category']->range = 'ul.main_con li';
    print_r($bookRule->bookList['category']->rules);
    $data = $ql
      ->range($bookRule->bookList['category']->range)
      ->rules($bookRule->bookList['category']->rules)
      ->query()->getData();
    dd($data);
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
