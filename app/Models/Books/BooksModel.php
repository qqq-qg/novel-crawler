<?php

namespace App\Models\Books;

use App\Models\BaseModel;

class BooksModel extends BaseModel
{
    protected $table = 'books';

    const UPT_STATUS_LOADING = 'loading';
    const UPT_STATUS_FINISHED = 'finished';
}
