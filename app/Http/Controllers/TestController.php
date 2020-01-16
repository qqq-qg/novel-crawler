<?php

namespace App\Http\Controllers;

use QL\QueryList;

class TestController extends Controller {
  public function index() {
    $config = config('book.zhengheng');

    $homeUrl = $config['baseUrl'] . '/book/912604.html';

    $book = QueryList::Query($homeUrl, $config['home'], '', 'UTF-8', 'GBK', true);

    dd($book);
  }
}
