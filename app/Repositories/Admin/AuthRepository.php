<?php

namespace App\Repositories\Admin;

use App\Models\Admin\ManagerModel;
use Hash;

class AuthRepository
{
  /**
   * @param array $credentials
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
   * @throws \Exception
   * @author Nacrane
   * @Date: 2020/04/26 18:15
   */
  public function validate(array $credentials)
  {
    return $this->validateExistUser($credentials);
  }

  /**
   * @param array $data
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
   * @throws \Exception
   * @author Nacrane
   * @Date: 2020/04/26 17:57
   */
  private function validateExistUser(array $data)
  {
    if (empty($data['username']) || empty($data['password'])) {
      throw new \Exception('请输入用户名或密码', 4220);
    }

    $user = ManagerModel::query()->where('username', $data['username'])->first();
    // 未找到用户
    if (!$user) {
      throw new \Exception('用户名或密码不正确', 4040);
    }

    if (Hash::check($data['password'], $user->password) === false) {
      throw new \Exception('用户名或密码不正确', 4220);
    }

    return $user;
  }
}
