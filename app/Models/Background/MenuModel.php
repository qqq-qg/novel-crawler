<?php

namespace App\Models\Background;

use App\Models\BaseModel;
/**
 * @property integer id
 * @property integer pid 父ID
 * @property string name 菜单名称
 * @property string prefix 路由前缀
 * @property string route 详细路由
 * @property string ico 图标名称
 * @property integer listorder 排序
 * @property integer items 子分类数量
 */
class MenuModel extends BaseModel
{
  protected $table = 'admin_menus';
}
