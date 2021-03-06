<?php

namespace App\Events;

use App\Models\Books\BooksModel;
use Illuminate\Queue\SerializesModels;

class BooksChangeSourceEvent
{
  use SerializesModels;
  public $book;

  public function __construct(BooksModel $book)
  {
    $this->book = $book;
  }
}
