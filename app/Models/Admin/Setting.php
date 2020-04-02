<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  protected $primaryKey = 'item';//设置自定义主键
  protected $fillable = [
    'item',
    'name',
    'value'
  ];
  public $timestamps = false;//禁止维护timestamps相关字段
}
