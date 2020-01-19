<?php

namespace App\Repositories;

use App\Models\Admin\Links;

class LinkRepository extends BaseRepository
{
    public function __construct(Links $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $condition
     * @param string $order
     * @param int $pagesize
     * @return mixed
     */
    public function lists($condition = [], $order = 'listorder DESC', $pagesize = 20)
    {
        $order = $order ? explode(' ', $order) : ['listorder', 'DESC'];
        return $this->model->where(array_merge(['status' => 1], $condition))
            ->orderBy($order[0], $order[1])
            ->take($pagesize)
            ->get()
            ->toArray();
    }
}
