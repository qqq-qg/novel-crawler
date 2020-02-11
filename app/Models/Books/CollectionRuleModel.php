<?php

namespace App\Models\Books;

use App\Models\BaseModel;

/**
 * Class CollectionRuleModel
 * @author Nacrane
 * @Date: 2020/02/11
 * @Time: 16:07
 * @package App\Models\Books
 *
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
