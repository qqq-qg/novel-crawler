<?php

namespace App\Http\Controllers;


class TestController extends Controller
{
    public function index()
    {
        $pattern = '/<div[\s\S]*?>\s*?(<p[\s\S]*?>[\s\S]*?<\/p>)\s*?<\/div>/';
        $pattern = '/<div\s*?.*?>\s*(<p\s*?.*?>[\s\S]*?<\/p>\s*?)<\/div>/';
        $string1 = <<<EOF
<div>
  <div class="a">
    <p>abc</p>
    <p class="p-c">
    de
    f</p></div>
</div>
EOF;
        if (preg_match($pattern, $string1, $arr)) {
            dd($arr);
        }
        dd(false);
    }
}
