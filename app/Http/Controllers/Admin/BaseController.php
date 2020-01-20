<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Manager;

class BaseController extends Controller
{
    protected static $userid;
    protected static $username;
    protected static $truename;
    protected static $user;
    protected $Manager;

    public function __construct()
    {
        $this->Manager = new Manager();
        $this->check_login();
    }

    /**
     * 检测用户登录
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function check_login()
    {
        $userinfo = session('userinfo');
        if ($userinfo) {
            $user = $this->Manager->query()->where(['username' => $userinfo['username']])->first();
            if ($user) {
                //确认密码
                if ($userinfo['password'] == substr(md5($user->password), 0, 8)) {
                    self::$userid = $user->id;
                    self::$username = $user->username;
                    self::$truename = $user->truename;
                    self::$user = $user;
                    return true;
                } else {
                    //密码不匹配，需要确认密码
                    return redirect()->route('getEnterpassword');
                }
            }
        }
        return redirect()->route('getAdminLogin');
    }
}
