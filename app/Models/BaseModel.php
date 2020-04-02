<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  protected $guarded = [];

  protected $casts = ['status' => 'string', 'visible' => 'string'];

  const ENUM_YES = 'Y';
  const ENUM_NO = 'N';

  const ENABLE_STATUS = 1;

  const DISABLE_STATUS = 2;

  const DEFAULT_STATUS = 0;

  public static $statusType = [
    self::ENABLE_STATUS => '启用',
    self::DISABLE_STATUS => '禁用'
  ];

  public static function getTableName()
  {
    return (new static())->getTable();
  }

  /**
   * The name of the "created at" column.
   *
   * @var string
   */
  const CREATED_AT = 'created_at';

  /**
   * The name of the "updated at" column.
   *
   * @var string
   */
  const UPDATED_AT = 'updated_at';
}
