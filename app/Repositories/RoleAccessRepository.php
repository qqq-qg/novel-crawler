<?php

namespace App\Repositories;

use App\Models\Admin\RoleAccess;

class RoleAccessRepository extends BaseRepository
{
  public function __construct(RoleAccess $model)
  {
    parent::__construct($model);
  }
}
