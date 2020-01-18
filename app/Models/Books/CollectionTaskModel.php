<?php

namespace App\Models\Books;

use App\Models\BaseModel;

class CollectionTaskModel extends BaseModel
{
    protected $table = 'collection_task';

    public function getTasks($id = null)
    {
        $query = static::query()
            ->with('rule')
            ->where('status', CollectionTaskModel::ENABLE_STATUS);
        if (!empty($id)) {
            $query->where('id', $id);
        }
        return $query->get();
    }

    public function rule()
    {
        return $this->hasOne(CollectionRuleModel::class, 'id', 'rule_id');
    }
}
