<?php

namespace App\Models\Background;

use App\Models\BaseModel;
/**
 * @property integer id
 * @property string title 名称
 * @property string linkurl 链接
 * @property integer listorder 排序
 * @property integer status 状态
 * @property string created_at
 * @property string updated_at
 */
class LinkModel extends BaseModel
{
  protected $table = 'links';
}
