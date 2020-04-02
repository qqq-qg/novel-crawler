<?php

namespace App\Models\Books;

use App\Models\BaseModel;
use App\Repositories\CollectionRule\BookRule;

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
   * @return array
   * @author Nacrane
   * @Date: 2020/02/29 @Time: 10:39
   */
  public static function getAllRuleReplaceTags()
  {
    $ruleReplaceTagsArr = [];
    /** @var CollectionRuleModel[] $allRules */
    $allRules = self::getAllRules();
    foreach ($allRules as $rule) {
      /** @var BookRule $bookRule */
      $bookRule = unserialize($rule->rule_json);
      foreach ($bookRule->replaceTags as $tag) {
        $ruleReplaceTagsArr[] = $tag;
      }
    }
    return $ruleReplaceTagsArr;
  }

  /**
   * @param $id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null|CollectionRuleModel
   * @author Nacrane
   * @Date: 2020/02/29 13:55
   */
  public static function getRuleById($id)
  {
    return self::query()
      ->where('status', CollectionRuleModel::ENABLE_STATUS)
      ->where('id', $id)
      ->first();
  }

  /**
   * @param $host
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null|CollectionRuleModel
   * @author Nacrane
   * @Date: 2020/02/29 @Time: 13:56
   */
  public static function getRuleByHost($host)
  {
    return self::query()
      ->where('status', CollectionRuleModel::ENABLE_STATUS)
      ->where('host', $host)
      ->first();
  }
}
