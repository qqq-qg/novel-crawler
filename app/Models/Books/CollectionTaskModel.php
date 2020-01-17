<?php

namespace App\Models\Books;

use App\Models\BaseModel;

class CollectionTaskModel extends BaseModel {
  protected $table = 'collection_task';

  public function rule() {
    return $this->hasOne(CollectionRuleModel::class, 'id', 'rule_id')->select('id', 'rule_json');
  }
}
