<?php

namespace App\Repositories\Admin;

use App\Models\Admin\ManagerModel;
use Hash;
use Illuminate\Database\Eloquent\Builder;

class ManagerRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(ManagerModel::query(), $search)->paginate($search['pageSize'] ?? 10);
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
      $data['password'] = Hash::make($data['password']);
      $model = ManagerModel::query()->create($data);
      if (!$model->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $model = ManagerModel::query()->where('id', $data['id'])->first();
      if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
      }
      $rst = $model->update($data);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $model;
  }

  public function show($id)
  {
    return ManagerModel::query()->find($id);
  }

  public function destroy($id)
  {
    $rst = ManagerModel::query()->where('id', $id)->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }
}
