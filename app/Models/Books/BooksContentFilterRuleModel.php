<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class BooksContentFilterRuleModel
 * @author Nacrane
 * @Date: 2020/02/11
 * @Time: 16:09
 * @package App\Models\Books
 *
 * @property integer id
 * @property integer books_id BookID
 * @property string rule 规则
 * @property integer listorder 排序
 * @property integer status 状态
 * @property string created_at
 * @property string updated_at
 */
class BooksContentFilterRuleModel extends BaseModel
{
    protected $table = 'books_content_filter_rule';
}
