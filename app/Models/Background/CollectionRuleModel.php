<?php

namespace App\Models\Background;

use App\Models\BaseModel;
/**
 * @property integer id
 * @property string title 标题
 * @property string host 域名
 * @property string rule_json 采集规则
 * @property integer status 状态,1-正常，2-无效
 * @property string created_at
 * @property string updated_at
 */
class CollectionRuleModel extends BaseModel
{
  protected $table = 'collection_rule';
}
