<?php

namespace App\Http\Controllers\Background;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
  /**
   * @return \Illuminate\View\View
   * @author Nacrane
   * @Date: 2020/04/08 22:44
   */
  public function index()
  {
    return view('background.index.index');
  }
}
