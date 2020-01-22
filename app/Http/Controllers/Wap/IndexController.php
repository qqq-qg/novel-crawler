<?php

namespace App\Http\Controllers\Wap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return view('wap/index');
    }
}
