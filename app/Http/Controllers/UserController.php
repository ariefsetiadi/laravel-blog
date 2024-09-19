<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Services\UserService;
use App\Services\RolePermissionService;

use App\Http\Requests\UserRequest;
use App\Http\Requests\ResetPasswordRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RolePermissionService $rolePermissionService,
    ) { }

    public function index()
    {
        $data['title']  =   'List User';
        $data['roles']  =   $this->rolePermissionService->getAllRole();

        if (request()->ajax()) {
            $users  =   $this->userService->getAllUserWithRole();

            return datatables()->of($users['data'])
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
        $result =   $this->userService->saveUser($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }

    public function edit($id)
    {
        $user   =   $this->userService->getUserWithRoleById($id);

        return response()->json(['data' => $user['data']]);
    }

    public function update(UserRequest $request)
    {
        $result =   $this->userService->updateUser($request);

        return response()->json([
                'success'   =>  $result['success'],
                'messages'  =>  $result['message'],
            ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result =   $this->userService->resetPassword($request);

        return response()->json([
                'success'   =>  $result['success'],
                'messages'  =>  $result['message'],
            ]);
    }
}
