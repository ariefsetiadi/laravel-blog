<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Services\ArticleService;
use App\Services\CategoryService;

use App\Http\Requests\ArticleRequest;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        protected CategoryService $categoryService,
    ) { }

    public function index()
    {
        $data['title']  =   'List Artikel';

        if (request()->ajax()) {
            $articles   =   $this->articleService->getAllArticleWithUserAndCategory();

            return datatables()->of($articles['data'])
                ->addColumn('action', function($data) {
                    if ($data->userId == Auth::user()->id) {
                        if (Auth::user()->can('Edit Artikel')) {
                            $button =   '<a href="'.route('article.edit', $data->id).'" class="btnEdit btn btn-warning">Edit</a>';

                            return $button;
                        }
                    }
                })->editColumn('status', function($data) {
                    return $data->status == TRUE ? '<span class="badge badge-success">Publish</span>' : '<span class="badge badge-danger">Draft</span>';
                })->editColumn('updated_at', function($data) {
                    Carbon::setLocale('id');

                    return Carbon::parse($data->updated_at)->translatedFormat('d M Y H:i');
                })->rawColumns(['action', 'status', 'updated_at'])->addIndexColumn()->make(true);
        }

        return view('Article.index', $data);
    }

    public function create()
    {
        $data['title']      =   'Tambah Artikel';
        $data['category']   =   $this->categoryService->getCategoryByCondition('status', true);
        $data['button']     =   'Simpan';
        $data['article']    =   '';

        return view('Article.form', $data);
    }

    public function store(ArticleRequest $request)
    {
        $result =   $this->articleService->saveArticle($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }

    public function edit($id)
    {
        $data['title']  =   'Edit Artikel';
        $query          =   $this->articleService->getArticleById($id);

        if ($query['success'] == false) {
            return view('Error.notfound', $data);
        }

        $data['category']   =   $this->categoryService->getCategoryByCondition('status', true);
        $data['button']     =   'Update';
        $data['article']    =   $query['data'];

        return view('Article.form', $data);
    }

    public function update(ArticleRequest $request)
    {
        $result =   $this->articleService->updateArticle($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }

    public function uploadImage(Request $request)
    {
        $result =   $this->articleService->uploadImageTinyMCE($request);

        return response()->json([
            'location'  =>  $result['data']['location'],
            'filename'  =>  $result['data']['filename'],
        ]);
    }

    public function deleteImage(Request $request)
    {
        $result =   $this->articleService->deleteImageTinyMCE($request);

        return response()->json([
            'success'   =>  $result['success'],
            'messages'  =>  $result['message'],
        ]);
    }
}
