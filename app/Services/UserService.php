<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use Auth;
use Hash;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserService {
	public function getAllUserWithRole() {
		try {
      $data	=	User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
                  ->leftJoin('roles', 'roles.id', 'model_has_roles.role_id')
                  ->select([
                    'users.*',
                    'roles.name as roleName',
                  ])
                  ->orderBy('users.id')
                  ->get();

      return responseSuccess($data, 200, 'Berhasil ambil data user');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Gagal ambil data user');
    }
	}

  public function getUserWithRoleById($id) {
    try {
      $data = User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
                  ->select([
                      'users.*',
                      'model_has_roles.role_id',
                      'model_has_roles.model_id',
                  ])
                  ->where('users.id', $id)
                  ->first();

      return responseSuccess($data, 200, 'Data user ditemukan');
    } catch (\Throwable $th) {
      return responseFailed(null, 404, 'Data user tidak ditemukan');
    }
  }

	public function saveUser($data) {
    DB::beginTransaction();

		try {
      $user				      = new User;
      $user->name       = $data['name'];
      $user->is_active  = $data['is_active'];
      $user->email      = strtolower($data['email']);
      $user->password   = Hash::make('Pass12345');
      $user->save();

      $role = Role::findOrFail($data['role']);
      $user->syncRoles($role);

      DB::commit();
      return responseSuccess($user, 201, 'User berhasil disimpan');
    } catch (\Throwable $th) {
      DB::rollBack();
      return responseFailed(null, 500, 'User gagal disimpan');
    }
	}

  public function updateUser($data) {
    DB::beginTransaction();

    try {
      $user = User::findOrFail($data['user_id']);

      if ($user && $user->id != Auth::user()->id) {
        $array  = array(
          'name'      =>  $data['name'],
          'email'     =>  strtolower($data['email']),
          'is_active' =>  $data['is_active'],
        );
  
        User::findOrFail($data['user_id'])->update($array);

        $role = Role::findOrFail($data['role']);
        $user->syncRoles($role);
      }

      DB::commit();
      return responseSuccess($user, 200, 'User berhasil diupdate');
    } catch (\Throwable $th) {
      DB::rollBack();
      return responseFailed(null, 500, 'User gagal diupdate');
    }
  }

  public function resetPassword($data) {
    try {
      $user = User::findOrFail($data['userReset_id']);

      if ($user && $user->id != Auth::user()->id) {
        $arr  = array(
          'password'          =>  Hash::make($data['password']),
          'default_password'  =>  true,
        );

        User::findOrFail($data['userReset_id'])->update($arr);
      }
      return responseSuccess($user, 200, 'Reset Password User berhasil');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Reset Password User gagal');
    }
  }

  public function changePassword($data) {
    try {
      $user = User::findOrFail(Auth::user()->id);

      if ($user && $user->id) {
        $arr  = array(
          'password'          =>  Hash::make($data['password']),
          'default_password'  =>  false,
        );

        User::findOrFail(Auth::user()->id)->update($arr);
      }
      return responseSuccess($user, 200, 'Ganti Password berhasil');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Ganti Password gagal');
    }
  }
}