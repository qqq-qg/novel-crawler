<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
  /**
   * @var Model $model
   */
  protected $model;
  
  protected static $pageSize = 15;

  /**
   * BaseRepository constructor.
   * @param Model $model
   */
  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  /**
   * @param $id
   * @return mixed
   */
  public function find($id)
  {
    return $this->model::query()->find($id);
  }

  /**
   * 新增
   * @param array $data
   * @return mixed
   */
  public function create($data)
  {
    return $this->model::query()->create($data);
  }

  /**
   * 更新
   * @param array $data
   * @return mixed
   */
  public function update($data)
  {
    $data = array_map(function ($val) {
      return $val ?? '';
    }, $data);
    $item = $this->find($data[$this->model->getKeyName()]);
    return $item->update($data);
  }

  /**
   * 删除
   * @param $id
   * @return int
   */
  public function delete($id)
  {
    return $this->model->destroy($id);
  }

  public function count()
  {
    return $this->model::query()->count();
  }
}
