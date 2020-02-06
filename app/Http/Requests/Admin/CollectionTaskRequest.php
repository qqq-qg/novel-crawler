<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CollectionTaskRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'from_url' => 'required',
            'rule_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id.integer' => 'ID 必须是数字',
            'title.required' => '任务名称 不能为空',
            'from_url.required' => '采集URL 不能为空',
            'rule_id.required' => '采集规则 不能为空',
            'rule_id.integer' => '采集规则 必须是数字',
        ];
    }
}
