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
        $lists = $this->model->where(array_merge(['status' => 1], $condition));
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
        $lists = $this->model->where('thumb', '<>', '')->where(array_merge(['status' => 1], $condition))->orderBy($order[0], $order[1]);
        if ($page) {
            return $lists->paginate($pagesize);
        } else {
            return $lists->take($pagesize)->get();
        }
    }

    /**
     * 获取源列表
     * @param null $status
     * @return array|mixed
     */
    public static function sourceLists($status = null)
    {
        $source = config('book.source');
        if ($status === null) {
            return $source;
        } else if ($status == 1) {
            $source = array_filter($source, function ($v) {
                if ($v['status'] == 1) return true;
            });
        } else if ($status == 0) {
            $source = array_filter($source, function ($v) {
                if ($v['status'] == 0) return true;
            });
        }
        return $source;
    }

    /**
     * @return mixed
     */
    public static function getCategorys()
    {
        return config('book.categorys');
    }

    public function dailyInsertCount()
    {
        return $this->model->where('created_at', '>', date('Y-m-d 00:00:00'))->count();
    }

    public function dailyUpdateCount()
    {
        return $this->model->where('updated_at', '>', date('Y-m-d 00:00:00'))->count();
    }

    public function monthInsertCount()
    {
        return $this->model->where('created_at', '>', date('Y-m-01 00:00:00'))->count();
    }

    public function monthUpdateCount()
    {
        return $this->model->where('updated_at', '>', date('Y-m-01 00:00:00'))->count();
    }
}
