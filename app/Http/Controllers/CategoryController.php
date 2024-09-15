<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CategoryRequest;

use App\Models\Category;

use Str;

class CategoryController extends Controller
{
    public function index()
    {
        $data['title']  =   'List Kategori';
        if (request()->ajax()) {
            return datatables()->of(Category::orderBy('id')->get())
                ->addColumn('action', function($data) {
                    if (Auth::user()->can('Edit Kategori')) {
                        $button =   '<button type="button" id="'.$data->id.'" class="btnEdit btn btn-warning">Edit</button>';

                        return $button;
                    }
                })->editColumn('status', function($data) {
                    return $data->status == TRUE ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>';
                })->rawColumns(['action', 'status'])->addIndexColumn()->make(true);
        }

        return view('Category.index', $data);
    }

    public function store(CategoryRequest $request)
    {
        $category                   =   new Category;
        $category->category_name    =   $request->category_name;
        $category->status           =   $request->status;
        $category->slug             =   Str::slug($request->category_name);
        $category->save();

        return response()->json(['messages' => 'Kategori berhasil ditambah']);
    }

    public function edit($id)
    {
        $category   =   Category::findOrFail($id);

        return response()->json(['data' => $category]);
    }

    public function update(CategoryRequest $request)
    {
        $data = array(
            'category_name' =>  $request->category_name,
            'status'        =>  $request->status,
            'slug'          =>  Str::slug($request->category_name),
        );

        Category::findOrFail($request->category_id)->update($data);

        return response()->json(['success' => true, 'messages' => 'Kategori berhasil diupdate']);
    }
}
