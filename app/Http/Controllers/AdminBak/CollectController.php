<?php

namespace App\Http\Controllers\AdminBak;

use Illuminate\Http\Request;

class CollectController extends BaseController
{
  /**
   * 首页
   * @param Request $request
   * @return mixed
   */
  public function getIndex(Request $request)
  {
    $data = [
      'lists' => []
    ];
    return admin_view('collect.index', $data);
  }
}
