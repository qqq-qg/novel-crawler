<?php

namespace App\Http\Controllers;

use App\Events\BooksChangeSourceEvent;
use App\Models\Books\BooksModel;

class TestController extends Controller
{
    public function index()
    {
        event(new BooksChangeSourceEvent(new BooksModel()));
    }
}
