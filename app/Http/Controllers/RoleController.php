<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\RoleRequest;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $data['title']          =   'List Role';
        $data['permissions']    =   Permission::get();

        if (request()->ajax()) {
            return datatables()->of(Role::get())
                ->addColumn('action', function($data) {
                    if (Auth::user()->can('Edit Role')) {
                        $button =   '<a href="'.route('role.edit', $data->id).'" class="btnEdit btn btn-warning">Edit</a>';

                        return $button;
                    }
                })->rawColumns(['action'])->addIndexColumn()->make(true);
        }

        return view('Role.index', $data);
    }

    public function create()
    {
        $data['title']          =   'Tambah Role';
        $data['role']           =   '';
        $data['permissions']    =   Permission::get();
        $data['button']         =   'Simpan';

        return view('Role.form', $data);
    }

    public function store(RoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $role   =   Role::create([
                'name' => $request->name,
            ]);
    
            $permissions    =   Permission::whereIn('id', $request->permission)->get();
            $role->syncPermissions($permissions);

            DB::commit();
            return response()->json(['messages' => 'Role berhasil ditambah']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return response()->json(['errors' => $th->getMessage()], 500);
            return response()->json(['errors' => 'Role gagal ditambah']);
        }
    }

    public function edit($id)
    {
        $getRole    =   Role::findOrFail($id);

        $data['title']              =   'Edit Role';
        $data['role']               =   $getRole;
        $data['permissions']        =   Permission::get();
        $data['rolePermissions']    =   $getRole->permissions->pluck('id')->toArray();
        $data['button']             =   'Update';

        return view('Role.form', $data);
    }

    public function update(RoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $role   =   Role::findOrFail($request->role_id);

            $role->update([
                'name' => $request->name
            ]);

            $permissions    =   Permission::whereIn('id', $request->permission)->get();
            $role->syncPermissions($permissions);

            DB::commit();
            return response()->json(['messages' => 'Role berhasil diupdate']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return response()->json(['errors' => $th->getMessage()], 500);
            return response()->json(['errors' => 'Role gagal diupdate']);
        }
    }
}
