<?php

namespace App\Repositories\Background;

use App\Models\Background\CategoryModel;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(CategoryModel::query(), $search)->paginate($search['pageSize'] ?? 10);
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
    return $query->orderBy('listorder');
  }

  public function store($data)
  {
    if (empty($data['id'])) {
      $companyModel = CategoryModel::query()->create($data);
      if (!$companyModel->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $companyModel = CategoryModel::query()->where('id', $data['id'])->first();
      $rst = $companyModel->update($data);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $companyModel->id;
  }

  public function show($id)
  {
    return CategoryModel::query()->find($id);
  }

  public function destroy($id)
  {
    $rst = CategoryModel::query()->whereIn('id', explode(',', $id))->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }

  public function all()
  {
    return CategoryModel::query()->orderBy('listorder', 'asc')->get();
  }
}
