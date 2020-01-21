<?php

namespace App\Models\Books;

use App\Models\BaseModel;

class CollectionRuleModel extends BaseModel
{
    protected $table = 'collection_rule';

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @Date: 2020/01/21 15:41
     */
    public static function getAllRules()
    {
        return self::query()
            ->where('status', CollectionRuleModel::ENABLE_STATUS)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @Date: 2020/01/21 15:48
     */
    public static function getRuleById(array $ids)
    {
        return self::query()
            ->where('status', CollectionRuleModel::ENABLE_STATUS)
            ->whereIn('id', $ids)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param $host
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @Date: 2020/01/21 15:44
     */
    public static function getRuleByHost($host)
    {
        return self::query()
            ->where('status', CollectionRuleModel::ENABLE_STATUS)
            ->where('rule_json', 'like', "%{$host}%")
            ->first();
    }
}
