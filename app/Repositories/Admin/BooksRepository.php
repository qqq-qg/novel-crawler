<?php namespace App\Repositories\Admin;

use App\Models\Admin\BooksModel;
use Illuminate\Database\Eloquent\Builder;

class BooksRepository
{
  public function index($search)
  {
    $paginate = $this->searchQuery(BooksModel::query(), $search)->paginate($search['pageSize'] ?? 10);
    $categoryService = new CategoryRepository();
    /** @var BooksModel $item */
    foreach ($paginate->items() as $k => $item) {
      $item->category_name = $categoryService->getCategoryName($item->cate_id);
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
    $data = transferNullValToStr($data);
    if (empty($data['id'])) {
      $companyModel = BooksModel::query()->create($data);
      if (!$companyModel->id) {
        throw new \Exception("新增保存失败");
      }
    } else {
      $companyModel = BooksModel::query()->where('id', $data['id'])->first();
      $rst = $companyModel->update($data);
      if (!$rst) {
        throw new \Exception("更新保存失败");
      }
    }
    return $companyModel->id;
  }

  public function show($id)
  {
    return BooksModel::query()->find($id);
  }

  public function destroy($id)
  {
    $rst = BooksModel::query()->where('id', explode(',', $id))->delete();
    if (!$rst) {
      throw new \Exception("删除失败");
    }
    return $rst;
  }
}
