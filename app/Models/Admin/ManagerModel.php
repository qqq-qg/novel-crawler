<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

/**
 * @property integer id
 * @property string username 用户名
 * @property string password 密码
 * @property string truename 真实姓名
 * @property string email 邮箱号码
 * @property string salt 校验码
 * @property string lastip 上一次登录ip
 * @property integer lasttime 最后登录时间
 * @property string remember_token
 * @property string created_at
 * @property string updated_at
 */
class ManagerModel extends BaseModel
{
  protected $table = 'managers';
}
