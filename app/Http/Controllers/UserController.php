<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\UserRequest;
use App\Http\Requests\ResetPasswordRequest;

use Auth;
use Hash;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $data['title']  =   'List User';
        $data['roles']  =   Role::get();

        if (request()->ajax()) {
            $user   =   User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
                            ->leftJoin('roles', 'roles.id', 'model_has_roles.role_id')
                            ->select([
                                'users.*',
                                'roles.name as roleName',
                            ])
                            ->orderBy('users.id')
                            ->get();

            return datatables()->of($user)
                ->addColumn('action', function($data) {
                    if ($data->id != Auth::user()->id) {
                        if (Auth::user()->can(['Edit User', 'Reset Password User'])) {
                            $button =   '<button type="button" id="'.$data->id.'" class="btnEdit btn btn-warning mr-1">Edit</button>';
                            $button .=   '<button type="button" id="'.$data->id.'" class="btnReset btn btn-danger ml-1">Reset Password</button>';
                        } else if (Auth::user()->can('Edit User')) {
                            $button =   '<button type="button" id="'.$data->id.'" class="btnEdit btn btn-warning">Edit</button>';
                        } else if (Auth::user()->can('Reset Password User')) {
                            $button =   '<button type="button" id="'.$data->id.'" class="btnReset btn btn-danger">Reset Password</button>';
                        }

                        return $button;
                    }
                })->editColumn('status', function($data) {
                    return $data->is_active == TRUE ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>';
                })->rawColumns(['action', 'status'])->addIndexColumn()->make(true);
        }

        return view('User.index', $data);
    }

    public function store(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user               =   new User;
            $user->name         =   $request->name;
            $user->is_active    =   $request->is_active;
            $user->email        =   strtolower($request->email);
            $user->password     =   Hash::make('Pass12345');
            $user->save();

            $role   =   Role::findOrFail($request->role);
            $user->syncRoles($role);

            DB::commit();
            return response()->json(['messages' => 'User berhasil ditambah']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return response()->json(['errors' => $th->getMessage()], 500);
            return response()->json(['errors' => 'User gagal ditambah']);
        }
    }

    public function edit($id)
    {
        $user   =   User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
                        ->select([
                            'users.*',
                            'model_has_roles.role_id',
                            'model_has_roles.model_id',
                        ])
                        ->where('users.id', $id)
                        ->first();

        return response()->json(['data' => $user]);
    }

    public function update(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user   =   User::findOrFail($request->user_id);

            if ($user && $user->id == Auth::user()->id) {
                return response()->json(['success' => false, 'messages' => 'User tidak dapat diupdate']);
            } else {
                $data = array(
                    'name'      =>  $request->name,
                    'email'     =>  strtolower($request->email),
                    'is_active' =>  $request->is_active,
                );
        
                User::findOrFail($request->user_id)->update($data);

                $role   =   Role::findOrFail($request->role);
                $user->syncRoles($role);

                DB::commit();
                return response()->json(['success' => true, 'messages' => 'User berhasil diupdate']);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            // return response()->json(['errors' => $th->getMessage()], 500);
            return response()->json(['errors' => 'User gagal diupdate']);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user   =   User::findOrFail($request->userReset_id);

        if ($user && $user->id == Auth::user()->id) {
            return response()->json(['success' => false, 'messages' => 'Password User tidak dapat direset']);
        } else {
            $arr = array(
                'password'  =>  Hash::make($request->password),
            );
            User::findOrFail($request->userReset_id)->update($arr);

            return response()->json(['success' => true, 'messages' => 'Password User berhasil direset']);
        }
    }
}
