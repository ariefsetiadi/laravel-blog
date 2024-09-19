<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Services\RolePermissionService;

use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function __construct(
        protected RolePermissionService $rolePermissionService,
    ) { }

    public function index()
    {
        $data['title']          =   'List Role';

        if (request()->ajax()) {
            $roles  =   $this->rolePermissionService->getAllRole();

            return datatables()->of($roles['data'])
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
        $data['permissions']    =   $this->rolePermissionService->getAllPermission();
        $data['button']         =   'Simpan';

        return view('Role.form', $data);
    }

    public function store(RoleRequest $request)
    {
        $result =   $this->rolePermissionService->saveRole($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }

    public function edit($id)
    {
        $getRole    =   $this->rolePermissionService->getRoleById($id);

        $data['title']              =   'Edit Role';
        $data['role']               =   $getRole['data'];
        $data['permissions']        =   $this->rolePermissionService->getAllPermission();
        $data['rolePermissions']    =   $getRole['data']->permissions->pluck('id')->toArray();
        $data['button']             =   'Update';

        return view('Role.form', $data);
    }

    public function update(RoleRequest $request)
    {
        $result =   $this->rolePermissionService->updateRole($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }
}
