<?php

namespace App\Repositories;

use App\Models\AdminBak\RoleAccess;

class RoleAccessRepository extends BaseRepository
{
  public function __construct(RoleAccess $model)
  {
    parent::__construct($model);
  }
}
