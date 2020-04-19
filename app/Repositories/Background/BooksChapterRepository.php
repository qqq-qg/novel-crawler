<?php namespace App\Repositories\Background;

use App\Models\Background\BooksChapterModel;
use Illuminate\Database\Eloquent\Builder;

class BooksChapterRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(BooksChapterModel::query(), $search)->paginate($search['pageSize'] ?? 10);
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
      $companyModel = BooksChapterModel::query()->create($data);
      if (!$companyModel->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $companyModel = BooksChapterModel::query()->where('id', $data['id'])->first();
      $rst = $companyModel->update($data);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $companyModel;
  }

  public function show($id)
  {
    return BooksChapterModel::query()->find($id);
  }

  public function destroy($id)
  {
    $rst = BooksChapterModel::query()->where('id', $id)->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }
}
