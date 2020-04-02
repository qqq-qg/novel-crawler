<?php

namespace App\Repositories;

use App\Models\Admin\RoleGroup;

class RoleGroupRepository extends BaseRepository
{
  public function __construct(RoleGroup $model)
  {
    parent::__construct($model);
  }
}
