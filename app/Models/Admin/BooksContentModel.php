<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

/**
 * @property integer id
 * @property string content 小说内容
 * @property string created_at
 * @property string updated_at
 */
class BooksContentModel extends BaseModel
{
    protected $table = 'books_content';
}
