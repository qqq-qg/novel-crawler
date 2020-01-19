<?php

namespace App\Repositories;

use App\Models\Admin\Setting;

class SettingRepository extends BaseRepository
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }


    public function lists()
    {
        return $this->model->all()->toArray();
    }

    /**
     * 新增
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        return $this->model->updateOrCreate(['item' => $data['item']], $data);
    }

    /**
     * 保存
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        foreach ($data as $k => $v) {
            $r = $this->model->find($k);
            if (!$r) {
                continue;
            } else {
                //$r->name = $v['name'];
                $r->value = $v['value'];
                $r->save();
            }
        }
        return true;
    }
}
