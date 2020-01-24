<?php

namespace App\Models\Books;

use App\Models\BaseModel;

class BooksContentModel extends BaseModel
{
    protected $table = 'books_content';

    public static function getContent($id)
    {
        return self::query()
            ->where('id', $id)
            ->first();
    }
}
