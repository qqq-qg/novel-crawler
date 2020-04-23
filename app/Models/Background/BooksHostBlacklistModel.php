<?php

namespace App\Models\Background;

use App\Models\BaseModel;

/**
 * @property integer id
 * @property string host 域名
 * @property integer status 状态,1-正常，2-无效
 * @property string created_at
 * @property string updated_at
 */
class BooksHostBlacklistModel extends BaseModel
{
  protected $table = 'books_host_blacklist';
}
