<?php

namespace App\Models\Background;

use App\Models\BaseModel;
/**
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
