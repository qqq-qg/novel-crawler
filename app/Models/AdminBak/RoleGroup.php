<?php

namespace App\Models\AdminBak;

use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{
  public $fillable = [
    'name',
    'access',
    'status'
  ];

  public function getAccessAttribute($value)
  {
    return $value ? explode(',', $value) : [];
  }

  public function setAccessAttribute($value)
  {
    $this->attributes['access'] = $value ? implode(',', $value) : '';
  }

  public function member()
  {
    return $this->hasMany('App\Models\Admin\Manager', 'groupid', 'id');
  }
}
