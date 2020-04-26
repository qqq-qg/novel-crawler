<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
  /**
   * @return \Illuminate\View\View
   * @author Nacrane
   * @Date: 2020/04/08 22:44
   */
  public function index()
  {
    $user = auth()->user();
    $data = [
      'username' => $user->username,
      'user_id' => $user->id,
    ];
    return view('admin.index.index', ['data' => $data]);
  }

  public function home()
  {

  }
}
