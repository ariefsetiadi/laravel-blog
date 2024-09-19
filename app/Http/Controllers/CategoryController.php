<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Services\CategoryService;

use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) { }

    public function index()
    {
        $data['title']  =   'List Kategori';

        if (request()->ajax()) {
            $categories =   $this->categoryService->getAllCategory();

            return datatables()->of($categories['data'])
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
        $result =   $this->categoryService->saveCategory($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }

    public function edit($id)
    {
        $category   =   $this->categoryService->getCategoryById($id);

        return response()->json(['data' => $category['data']]);
    }

    public function update(CategoryRequest $request)
    {
        $result =   $this->categoryService->updateCategory($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }
}
