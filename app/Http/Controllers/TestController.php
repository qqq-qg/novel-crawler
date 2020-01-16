<?php

namespace App\Http\Controllers;

use QL\QueryList;

class TestController extends Controller
{
    public function index()
    {
        $config = config('book.wx999');

        $book = QueryList::Query($config[''], $config['detail'], '', 'UTF-8', 'GBK', true);

        //
    }
}
