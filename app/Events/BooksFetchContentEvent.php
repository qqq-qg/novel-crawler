<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class BooksFetchContentEvent
{
  use SerializesModels;
  public $bookId = 0;

  public function __construct($bookId)
  {
    $this->bookId = $bookId;
  }
}
