<?php

namespace App\Repositories\Admin;

use App\Models\Admin\CollectionRuleModel;
use Illuminate\Database\Eloquent\Builder;

class CollectionRuleRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(CollectionRuleModel::query(), $search)->paginate($search['pageSize'] ?? 10);
    foreach ($paginate->items() as $k => $item) {
      //todo
    }
    return $paginate;
  }

  private function searchQuery(Builder $query, $search)
  {
    if (!empty($search['status'])) {
      $query->where('status', $search['status']);
    }
    return $query->orderByDesc('id');
  }

  public function store($data)
  {
    if (empty($data['id'])) {
      $model = CollectionRuleModel::query()->create($data);
      if (!$model->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $model = CollectionRuleModel::query()->where('id', $data['id'])->first();
      $rst = $model->update($data);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $model->id;
  }

  public function show($id)
  {
    return CollectionRuleModel::query()->find($id);
  }

  public function destroy($id)
  {
    $rst = CollectionRuleModel::query()->where('id', $id)->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }


  public function all()
  {
    return CollectionRuleModel::query()->orderBy('id')->get();
  }

  public function getRuleName($ruleId)
  {
    static $rules = null;
    if (is_null($rules)) {
      $res = CollectionRuleModel::query()->select(['id', 'title'])->get()->toArray();
      $rules = array_column($res, 'title', 'id');
    }
    return $rules[$ruleId] ?? '';
  }
}
