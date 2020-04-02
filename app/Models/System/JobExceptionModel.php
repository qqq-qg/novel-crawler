<?php

namespace App\Models\System;

use App\Models\BaseModel;

class JobExceptionModel extends BaseModel
{
  protected $table = 'job_exception';

  const NORMAL_STATUS = 1;
  const ABNORMAL_STATUS = 2;

  public static function saveException($data)
  {
    return self::query()->create($data);
  }
}
