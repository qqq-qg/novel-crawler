<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeixinController extends BaseController
{

    public function getIndex(Request $request)
    {
        $lists = DB::table('weixin_chat')->paginate();
        $data = [
            'lists' => $lists,
        ];
        return admin_view('weixin.index', $data);
    }

    public function getUsers(Request $request)
    {

    }
}
