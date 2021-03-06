<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\SettingRepository;
use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function __construct(Request $request)
  {
    //通用数据
    if (!\Request::ajax() && \Request::isMethod('get')) {
      //System Setting
      $settings = \Cache::remember('settings', 600, function () {
        $settings = SettingRepository::lists();
        return array_column($settings, 'value', 'key');
      });
      view()->share('SET', $settings);

      //Normal param
      $catid = empty($request->catid) ? 0 : intval($request->catid);
      view()->share('catid', $catid);
      $id = empty($request->id) ? 0 : intval($request->id);
      view()->share('id', $id);
      $aid = empty($request->aid) ? 0 : intval($request->aid);
      view()->share('aid', $aid);

      //categories
      $categories = CategoryRepository::getCategories();
      view()->share('categories', $categories);
      view()->share('CAT', $catid ? $categories[$catid] : []);
    }
  }
}
