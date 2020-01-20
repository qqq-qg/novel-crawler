<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ManagerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        //排除的路由
        $except = [
            'getEnterpassword',
            'getLogout',
        ];
        $this->middleware('admin.guest', ['except' => $except]);
    }

    /**
     * 登录验证
     * @param $data
     * @return mixed
     */
    protected function validate_login($data)
    {
        return Validator::make($data, [
            'username' => 'required|string|min:4|max:255',
            'password' => 'required|string|min:4|max:255',
            'captcha' => 'required|captcha'
        ]);
    }

    /**
     * 注册校验
     * @param $data
     * @return mixed
     */
    protected function validate_register($data)
    {
        return Validator::make($data, [
            'username' => 'required|string|min:4|max:50|unique:managers',
            'truename' => 'required|string',
            'password' => 'required|string|min:4|max:255',
            'password_confirm' => 'same:password',
        ]);
    }

    /**
     * 用户登录
     * @return mixed
     */
    public function getLogin()
    {
        return admin_view('auth.login');
    }

    /**
     * 用户登录
     * @param Request $request
     * @param ManagerRepository $managerRepository
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function postLogin(Request $request, ManagerRepository $managerRepository)
    {
        $data = $request->all();
        $validator = $this->validate_login($data);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $user = $managerRepository->findBy('username', $data['username']);
        if ($user) {
            if ($managerRepository->compare_password($data['password'], $user->password)) {
                $managerRepository->loginRecord($user->username, $request->ip());
                $this->make_login_session($user, $managerRepository);
                return redirect()->route('Admin.getIndex');
            } else {
                return back()->withErrors('密码不正确')->withInput();
            }
        } else {
            return back()->withErrors('用户不存在')->withInput();
        }
    }

    /**
     * @param $user
     * @param ManagerRepository $managerRepository
     * @author Nacrane
     * @Date: 2020/01/20
     * @Time: 16:28
     */
    protected function make_login_session($user, ManagerRepository $managerRepository)
    {
        $userinfo = [
            'userid' => $user->id,
            'username' => $user->username,
            'password' => $managerRepository->session_use_password($user->password),
        ];

        session(['userinfo' => $userinfo]);
    }

    /**
     * 用户注册
     * @return mixed
     */
    public function getRegister()
    {
        return admin_view('auth.register');
    }

    /**
     * 用户注册
     * @param Request $request
     * @param ManagerRepository $managerRepository
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function postRegister(Request $request, ManagerRepository $managerRepository)
    {
        $data = $request->all();
        $validator = $this->validate_register($data);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $result = $managerRepository->create($data);
        if ($result) {
            return redirect()->route('Admin.getIndex');
        } else {
            return back()->withErrors('创建失败')->withInput();
        }
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        session()->forget('userinfo');
        return redirect(route('getAdminLogin'));
    }

    /**
     * @param Request $request
     * @param ManagerRepository $managerRepository
     * @return $this|\Illuminate\Http\RedirectResponse|mixed
     */
    public function getEnterpassword(Request $request, ManagerRepository $managerRepository)
    {
        if ($request->isMethod('POST')) {
            $password = $request->password;
            if ($managerRepository->compare_password($password, self::$user->password)) {
                $this->make_login_session(self::$user, $managerRepository);
                return redirect()->route('Admin.getIndex');
            }
            return back()->withErrors('请输入正确的密码')->withInput();
        } else {
            return admin_view('auth.enterpassword');
        }
    }
}
