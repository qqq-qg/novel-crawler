<?php

namespace App\Repositories\Admin;

use App\Models\Books\CollectionTaskModel;
use Illuminate\Database\Eloquent\Builder;

class CollectionTaskRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(CollectionTaskModel::query(), $search)->paginate($search['pageSize'] ?? 10);
    $categoryService = new CategoryRepository();
    $ruleService = new CollectionRuleRepository();
    /** @var CollectionTaskModel $item */
    foreach ($paginate->items() as $k => $item) {
      $item->category_name = $categoryService->getCategoryName($item->cate_id);
      $item->rule_name = $ruleService->getRuleName($item->rule_id);
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
      $companyModel = CollectionTaskModel::query()->create($data);
      if (!$companyModel->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $companyModel = CollectionTaskModel::query()->where('id', $data['id'])->first();
      $rst = $companyModel->update($data);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $companyModel;
  }

  public function show($id)
  {
    return CollectionTaskModel::query()->find($id);
  }

  public function destroy($id)
  {
    $rst = CollectionTaskModel::query()->where('id', $id)->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }
}
