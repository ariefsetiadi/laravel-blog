<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Requests\ResetPasswordRequest;

use Auth;
use Hash;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $data['title']  =   'List User';
        if (request()->ajax()) {
            return datatables()->of(User::orderBy('id')->get())
                ->addColumn('action', function($data) {
                    if ($data->id != Auth::user()->id) {
                        $button =   '<button type="button" id="'.$data->id.'" class="btnEdit btn btn-warning mr-1">Edit</button>';
                        $button .=   '<button type="button" id="'.$data->id.'" class="btnReset btn btn-danger ml-1">Reset Password</button>';

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
        $user               =   new User;
        $user->name         =   $request->name;
        $user->is_active    =   $request->is_active;
        $user->email        =   strtolower($request->email);
        $user->password     =   Hash::make('Pass12345');
        $user->save();

        return response()->json(['messages' => 'User berhasil ditambah']);
    }

    public function edit($id)
    {
        $user   =   User::findOrFail($id);

        return response()->json(['data' => $user]);
    }

    public function update(UserRequest $request)
    {
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
    
            return response()->json(['success' => true, 'messages' => 'User berhasil diupdate']);
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
