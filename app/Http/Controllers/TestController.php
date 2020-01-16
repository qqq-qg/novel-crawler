<?php

namespace App\Http\Controllers;

use App\Jobs\BooksJob;
use QL\QueryList;

class TestController extends Controller {

  private $config;

  public function __construct() {
    $this->config = config('book.zhengheng');
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
    $url = $this->config['baseUrl'] . '/book/912604.html';

    $config = config('book.zhengheng');
    dispatch(new BooksJob($url, $config));
  }
}
