<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class BooksContentModel
 * @author Nacrane
 * @Date: 2020/02/11
 * @Time: 16:09
 * @package App\Models\Books
 *
 * @property integer id
 * @property string content 小说内容
 * @property string created_at
 * @property string updated_at
 */
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
