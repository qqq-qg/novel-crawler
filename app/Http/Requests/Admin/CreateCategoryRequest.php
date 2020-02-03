<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CreateCategoryRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => 'integer',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.integer' => 'ID 必须是数字',
            'name.required' => '分类名称 不能为空',
        ];
    }
}
