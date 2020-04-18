<?php

namespace App\Models\Background;

use App\Models\BaseModel;
/**
 * @property integer id
 * @property string key
 * @property string name
 * @property string value
 */
class SettingModel extends BaseModel
{
  protected $table = 'settings';
}
