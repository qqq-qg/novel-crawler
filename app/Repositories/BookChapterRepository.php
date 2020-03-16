<?php

namespace App\Repositories;

use App\Models\Books\BooksChapterModel;

class BookChapterRepository extends BaseRepository {
  public function __construct(BooksChapterModel $model) {
    parent::__construct($model);
  }

  /**
   * @param array $condition
   * @param string $order
   * @param int $pagesize
   * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
   */
  public function lists($condition = [], $order = 'id DESC', $pagesize = 20) {
    $fields = ['id', 'books_id', 'chapter_index', 'title', 'created_at', 'updated_at'];
    $order = $order ? explode(' ', $order) : ['id', 'DESC'];
    return $this->model::query()->select($fields)
      ->where(array_merge(['status' => 1], $condition))
      ->orderBy($order[0], $order[1])
      ->paginate($pagesize);
  }

  /**
   * @param $pid
   * @return string
   */
  protected function getContentPath($pid) {
    return public_path("uploads/contents/{$pid}/");
  }

  /**
   * @param $pid
   * @param $id
   * @return string
   */
  public function getContent($pid, $id) {
    $bookDir = $this->getContentPath($pid);
    $search = [
      '推荐一个淘宝天猫内部折扣优惠券的微信公众号:guoertejia每天人工筛选上百款特价商品。打开微信添加微信公众号:guoertejia 省不少辛苦钱。'
    ];
    $replace = '';
    try {
      $content = \File::get($bookDir . "{$id}.txt");
      return str_replace($search, $replace, $content);
    } catch (\Exception $exception) {
      return '';
    }
  }

  /**
   * @param $pid
   * @param $id
   * @param $content
   * @return int
   */
  function setContent($pid, $id, $content) {
    $bookDir = $this->getContentPath($pid);
    if (!\File::isDirectory($bookDir)) {
      \File::makeDirectory($bookDir, 0777, true);
    }
    return \File::put($bookDir . "{$id}.txt", $content);
  }

  /**
   * 删除内容文本
   * @param $pid
   * @param $id
   * @return bool
   */
  function deleteContent($pid, $id) {
    $bookDir = $this->getContentPath($pid);
    $path = $bookDir . "{$id}.txt";

    if (\File::exists($path)) {
      return \File::delete($path);
    }
    return false;
  }

  public function updateDetail($data) {
    $item = $this->find($data['id']);
    if (!$item) return false;
    $item->title = $data['title'];
    $item->status = $data['status'];
    return $item->save();
  }

  /**
   * @param $pid
   * @param $aid
   * @return mixed|static
   */
  public function nextPage($pid, $aid) {
    return $this->model::query()->select('id')
      ->where('books_id', $pid)
      ->where('id', '>', $aid)
      ->orderBy('id', 'ASC')
      ->first();
  }

  /**
   * @param $pid
   * @param $aid
   * @return mixed|static
   */
  public function prevPage($pid, $aid) {
    return $this->model::query()->select('id')
      ->where('books_id', $pid)
      ->where('id', '<', $aid)
      ->orderBy('id', 'DESC')
      ->first();
  }

  /**
   * @param $pid
   * @return mixed|static
   */
  public function lastDetail($pid) {
    return $this->model::query()->select()
      ->where('books_id', $pid)
      ->orderBy('chapter_index', 'DESC')
      ->first();
  }

  /**
   * 今日新增
   * @return int
   */
  public function dailyInsertCount() {
    return $this->model::query()->where('created_at', '>', date('Y-m-d 00:00:00'))->count();
  }
}
