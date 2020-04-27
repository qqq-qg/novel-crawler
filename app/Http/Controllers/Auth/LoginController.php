<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AuthRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/index';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  /**
   * 登录表单
   *
   * @param AuthRepository $authRepo
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
   * @author Nacrane
   * @Date: 2020/04/26 16:31
   */
  public function loginForm()
  {
    if (auth()->check()) {
      return redirect()->route('admin.Index.index');
    }

    return view('admin.auth.login');
  }

  /**
   * 账号密码登录
   *
   * @param Request $request
   * @param AuthRepository $authRepo
   * @return \Illuminate\Http\JsonResponse
   * @author Nacrane
   * @Date: 2020/04/26 16:32
   */
  public function login(Request $request, AuthRepository $authRepo)
  {
    try {
      $credentials = [
        'username' => trim($request->input('username')),
        'password' => $request->input('password'),
      ];
      $user = $authRepo->validate($credentials);
      auth()->login($user);
      return Response::json(['code' => 0, 'message' => 'success', 'data' => route('admin.Index.index')]);
    } catch (\Exception $e) {
      return Response::json(['code' => 500, 'message' => $e->getMessage()]);
    }
  }

  /**
   * 退出
   *
   * @return \Illuminate\Http\RedirectResponse
   * @author Nacrane
   * @Date: 2020/04/26 16:35
   */
  public function logout()
  {
    $data = [
      'username' => auth()->user()->username,
      'user_id' => auth()->user()->id,
      'ip' => request()->getClientIp(),
    ];
    try {
      auth()->logout();
      session()->flush();
    } catch (\Exception $e) {
    }
    return redirect()->route('login-form');
  }
}
