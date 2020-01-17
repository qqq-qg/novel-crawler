<?php

namespace App\Http\Controllers;

use App\Jobs\BooksJob;
use App\Models\Books\CollectionRuleModel;
use App\Models\Books\CollectionTaskModel;
use QL\QueryList;

class TestController extends Controller {

  private $config;

  public function __construct() {
    $this->config = config('book.zhengheng');
  }

  public function category() {
    $url = $this->config['baseUrl'] . '/rank/details.html?rt=8&d=1&p={$page}';
    //    file_put_contents('category.html', QueryList::get($url)->getHtml());
    //    die;
    $url = 'http://www.ql.com/category.html';

    $data = QueryList::get($url)
      ->range($this->config['category']['range'])
      ->rules($this->config['category']['rules'])
      ->query()->getData();
    print_r($data->pluck('url')->all());

die;
    for ($i = 1; $i <= 3; $i++) {
      $findUrl = str_replace('{$page}', $i, $url);
      //      var_dump($findUrl);
      //      continue;
      $data = QueryList::get($findUrl)
        ->range($this->config['category']['range'])
        ->rules($this->config['category']['rules'])
        ->query()->getData();
      print_r($data->all());
    }
  }

  public function home() {
    $url = $this->config['baseUrl'] . '/book/912604.html';
    //    file_put_contents('home.html', QueryList::get($url)->getHtml());
    $url = 'http://www.ql.com/home.html';

    $data = QueryList::get($url)
      ->range($this->config['home']['range'])
      ->rules($this->config['home']['rules'])
      ->query()->getData();
    dd($data->all());
  }

  public function getChapter() {
    $url = $this->config['baseUrl'] . '/showchapter/912604.html';
    //    file_put_contents('getChapter.html', QueryList::get($url)->getHtml());
    $homeUrl = 'http://www.ql.com/getChapter.html';

    $data = QueryList::get($homeUrl)
      ->range($this->config['chapter_list']['range'])
      ->rules($this->config['chapter_list']['rules'])
      ->query()->getData();
    dd($data->all());
  }

  public function content() {
    $url = $this->config['baseUrl'] . '/chapter/912604/59047804.html';
    //    file_put_contents('content.html', QueryList::get($url)->getHtml());
    $homeUrl = 'http://www.ql.com/content.html';

    $data = QueryList::get($homeUrl)
      ->range($this->config['chapter_list']['range'])
      ->rules($this->config['content']['rules'])
      ->query()->getData();
    dd($data->all());
  }

  public function test() {


    $model = new CollectionRuleModel();
    $model->id = 1;
    $model->title = '纵横中文网';
    $model->rule_json = json_encode(config('book.zhengheng'));
    $model->save();

    $model = new CollectionTaskModel();
    $model->id = 1;
    $model->title = '纵横月票榜';
    $model->from_url = 'http://www.zongheng.com/rank/details.html?rt=1&d=1&i=2&p={$page}';
    $model->from_hash = md5($model->from_url);
    $model->rule_id = 1;
    $model->page_limit = 2;
    $model->task_code = 1;
    $model->save();

    die;
    $url = $this->config['baseUrl'] . '/book/912604.html';

    $config = config('book.zhengheng');
    dispatch(new BooksJob($url, $config));
  }
}
