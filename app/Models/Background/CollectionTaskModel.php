<?php

namespace App\Models\Background;

use App\Models\BaseModel;
/**
 * @property integer id
 * @property string title 标题
 * @property integer cate_id 分类ID
 * @property string from_url 采集链接
 * @property string from_hash 采集链接
 * @property integer rule_id 采集规则
 * @property integer page_limit 采集页数
 * @property integer current_page 当前采集页数
 * @property integer task_code 采集状态，1-就绪，2-采集中，200-已完成
 * @property integer status 状态,1-正常，2-无效
 * @property integer retries 尝试次数
 * @property string created_at
 * @property string updated_at
 */
class CollectionTaskModel extends BaseModel
{
  protected $table = 'collection_task';
}
