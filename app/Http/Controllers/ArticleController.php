<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Services\ArticleService;
use App\Services\CategoryService;

use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ReviewArticleRequest;

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
            if (Auth::user()->can('Review Artikel')) {
                $articles   =   $this->articleService->getAllArticleWithUserAndCategory();
            } else {
                $articles   =   $this->articleService->getAllArticleWithUserAndCategoryByAuth();
            }

            return datatables()->of($articles['data'])
                ->addColumn('action', function($data) {
                    $button = '';

                    if ($data->userId == Auth::user()->id) {
                        if (Auth::user()->can('Edit Artikel') && $data->status != 1) {
                            $button =   '<a href="'.route('article.edit', $data->id).'" class="btnEdit btn btn-warning">Edit</a>';
                        }
                    } else if (Auth::user()->can('Review Artikel') && $data->status == 1) {
                        $button =   '<a href="'.route('article.review', $data->id).'" class="btnReview btn btn-success">Review</a>';
                    }

                    return $button;
                })->editColumn('status', function($data) {
                    if ($data->status == 0) {
                        $status = '<span class="badge badge-secondary">Draft</span>';
                    } else if ($data->status == 1) {
                        $status = '<span class="badge badge-warning">On Review</span>';
                    } else if ($data->status == 2) {
                        $status = '<span class="badge badge-danger">Revise</span>';
                    } else if ($data->status == 3) {
                        $status = '<span class="badge badge-success">Publish</span>';
                    }

                    return $status;
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
        $data['button']     =   'Submit';
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
        $data['button']     =   'Submit';
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

    public function review($id)
    {
        $data['title']  =   'Review Artikel';
        $query          =   $this->articleService->getReviewArticle($id);

        if ($query['success'] == false) {
            return view('Error.notfound', $data);
        }

        $data['article']    =   $query['data'];
        $data['btnRevise']  =   'Revise';
        $data['btnPublish'] =   'Publish';

        return view('Article.review', $data);
    }

    public function updateReview(ReviewArticleRequest $request)
    {
        $result =   $this->articleService->updateReviewArticle($request);

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
