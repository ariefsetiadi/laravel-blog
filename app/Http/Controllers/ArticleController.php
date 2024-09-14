<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ArticleRequest;

use Auth;
use Carbon\Carbon;
use File;
use Str;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $data['title']  =   'List Artikel';

        $articles   =   Article::join('users', 'users.id', 'articles.created_by')
                                ->join('categories', 'categories.id', 'articles.category_id')
                                ->select([
                                    'articles.id', 'articles.title', 'articles.status', 'articles.slug', 'articles.updated_at',
                                    'users.id as userId', 'users.name as userName',
                                    'categories.id as catId', 'categories.category_name'
                                ])->orderBy('articles.created_at', 'DESC')
                                ->get();

        if (request()->ajax()) {
            return datatables()->of($articles)
                ->addColumn('action', function($data) {
                    if ($data->userId == Auth::user()->id) {
                        $button =   '<a href="'.route('article.edit', $data->id).'" class="btnEdit btn btn-warning">Edit</a>';

                        return $button;
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
        $data['category']   =   Category::where('status', true)->get();
        $data['button']     =   'Simpan';
        $data['article']    =   '';

        return view('Article.form', $data);
    }

    public function store(ArticleRequest $request)
    {
        // Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            $path       =   'uploads/articles/thumbnails';
            $thumbnail  =   $request->thumbnail;

            // Create folder if haven't
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0775, true, true);
            }

            $thumbnail_name =   'thumbnail-' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path($path), $thumbnail_name);
        }

        $article                =   new Article;
        $article->category_id   =   $request->category_id;
        $article->title         =   $request->title;
        $article->thumbnail     =   $thumbnail_name;
        $article->content       =   $request->content;
        $article->status        =   $request->status;
        $article->slug          =   Str::slug($request->title);
        $article->created_by    =   Auth::user()->id;
        $article->updated_by    =   Auth::user()->id;
        $article->save();

        return response()->json(['messages' => 'Artikel berhasil ditambah']);
    }

    public function edit($id)
    {
        $data['title']  =   'Edit Artikel';
        $query          =   Article::where('id', $id)
                                    ->where('created_by', Auth::user()->id)
                                    ->first();

        if (!$query) {
            return view('Error.notfound', $data);
        }

        $data['category']   =   Category::where('status', true)->get();
        $data['button']     =   'Update';
        $data['article']    =   $query;

        return view('Article.form', $data);
    }

    public function update(ArticleRequest $request)
    {
        $article        =   Article::findOrFail($request->article_id);
        $thumbnail_name =   $article->thumbnail;

        // Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            $path       =   'uploads/articles/thumbnails';
            $thumbnail  =   $request->thumbnail;

            // Create folder if haven't
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0775, true, true);
            }

            // Delete thumbnail if any upload other thumbnail
            if ($article->thumbnail != NULL) {
                File::delete('uploads/articles/thumbnails/' . $article->thumbnail);
            }

            $thumbnail_name =   'thumbnail-' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path($path), $thumbnail_name);
        }

        $data   =   array(
            'category_id'   =>  $request->category_id,
            'title'         =>  $request->title,
            'thumbnail'     =>  $thumbnail_name,
            'content'       =>  $request->content,
            'status'        =>  $request->status,
            'slug'          =>  Str::slug($request->title),
            'updated_by'    =>  Auth::user()->id,
        );

        Article::findOrFail($request->article_id)->update($data);

        return response()->json(['messages' => 'Artikel berhasil diupdate']);
    }

    public function uploadImage(Request $request)
    {
        $filename   =   'article_' . round(microtime(true) * 1000) . '.' . $request->file('file')->getClientOriginalExtension();
        $path       =   $request->file('file')->storeAs('uploads/articles', $filename, 'public');
        return response()->json([
            'location'  => "/storage/$path",
            'filename'  =>  $filename,
        ]);
    }

    public function deleteImage(Request $request)
    {
        $path   =   public_path($request->imagePath);

        if (File::exists($path)) {
            File::delete($path);
            return response()->json(['message'  =>  'Gambar dihapus'], 200);
        }

        return response()->json(['message'  =>  'Gambar tidak ditemukan'], 404);
    }
}
