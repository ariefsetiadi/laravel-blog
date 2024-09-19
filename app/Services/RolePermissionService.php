<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionService {
	public function getAllRole() {
		try {
      $data	=	Role::orderBy('id')->get();

      return responseSuccess($data, 200, 'Berhasil ambil data role');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data role');
    }
	}

  public function getRoleById($id) {
    try {
      $data = Role::findOrFail($id);

      return responseSuccess($data, 200, 'Data role ditemukan');
    } catch (\Throwable $th) {
      return responseFailed(null, 404, 'Data user tidak ditemukan');
    }
  }

  public function saveRole($data) {
    DB::beginTransaction();

    try {
      $role = Role::create([
                'name' => $data['name'],
              ]);

      $permissions  = Permission::whereIn('id', $data['permission'])->get();
      $role->syncPermissions($permissions);

      DB::commit();
      return responseSuccess($role, 201, 'Role berhasil disimpan');
    } catch (\Throwable $th) {
      DB::rollBack();
      return responseFailed(null, 500, 'Role gagal disimpan');
    }
  }

  public function updateRole($data) {
    DB::beginTransaction();

    try {
      $role = Role::findOrFail($data['role_id']);

      $role->update([
        'name' => $data['name']
      ]);

      $permissions  = Permission::whereIn('id', $data['permission'])->get();
      $role->syncPermissions($permissions);

      DB::commit();
      return responseSuccess($role, 200, 'Role berhasil diupdate');
    } catch (\Throwable $th) {
      DB::rollBack();
      return responseFailed(null, 500, 'Role gagal diupdate');
    }
  }

  public function getAllPermission() {
		try {
      $data	=	Permission::orderBy('id')->get();

      return responseSuccess($data, 200, 'Berhasil ambil data permission');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data permission');
    }
	}
}