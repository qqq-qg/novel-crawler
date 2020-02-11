<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class CategoryModel
 * @author Nacrane
 * @Date: 2020/02/11
 * @Time: 16:08
 * @package App\Models\Books
 *
 * @property integer id
 * @property string name 分类名称
 * @property integer listorder 排序，越小越靠前
 * @property string created_at
 * @property string updated_at
 */
class CategoryModel extends BaseModel
{
    protected $table = 'category';
}
