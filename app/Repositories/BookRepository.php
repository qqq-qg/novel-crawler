<?php

namespace App\Repositories;

use App\Models\Books\BooksModel;

class BookRepository extends BaseRepository
{
  public function __construct()
  {
    parent::__construct(new BooksModel());
  }

  /**
   * 普通列表
   * @param array $condition
   * @param string $order
   * @param int $pagesize
   * @param bool $page
   * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|static[]
   */
  public function lists($condition = [], $order = 'id DESC', $pagesize = 10, $page = true)
  {
    $lists = $this->model::query()->where(array_merge(['status' => 1], $condition));
    if (strpos($order, ',') !== false) {
      foreach (explode(',', $order) as $v) {
        $tmp = explode(' ', $order);
        $lists->orderBy($tmp[0], $tmp[1]);
      }
    } else {
      $order = $order ? explode(' ', $order) : ['id', 'DESC'];
      $lists->orderBy($order[0], $order[1]);
    }
    if ($page) {
      return $lists->paginate($pagesize);
    } else {
      return $lists->take($pagesize)->get();
    }
  }

  /**
   * 封面推荐
   * @param array $condition
   * @param string $order
   * @param int $pagesize
   * @param bool $page
   * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|static[]
   */
  public function ftlists($condition = [], $order = 'id DESC', $pagesize = 10, $page = false)
  {
    $order = $order ? explode(' ', $order) : ['id', 'DESC'];
    $lists = $this->model::query()->where('thumb', '<>', '')->where(array_merge(['status' => 1], $condition))->orderBy($order[0], $order[1]);
    if ($page) {
      return $lists->paginate($pagesize);
    } else {
      return $lists->take($pagesize)->get();
    }
  }
}
