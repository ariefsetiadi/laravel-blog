<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{
    public function index()
    {
        $articles   =   Article::join('users', 'users.id', 'articles.created_by')
                                ->join('categories', 'categories.id', 'articles.category_id')
                                ->where('articles.status', true)
                                ->select([
                                    'articles.title', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                                    'users.name as userName',
                                    'categories.category_name'
                                ])->orderBy('articles.created_at', 'desc')
                                ->paginate(6);

        $data['title']      =   'Semua Artikel';
        $data['articles']   =   $articles;
        $data['populars']   =   Article::where('status', true)->orderBy('created_at', 'desc')->take(5)->get();

        return view('Guest.Article.index', $data);
    }

    public function read($year, $month, $slug)
    {
        $article    =   Article::join('users', 'users.id', 'articles.created_by')
                                ->join('categories', 'categories.id', 'articles.category_id')
                                ->select([
                                    'articles.id', 'articles.title', 'articles.category_id', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                                    'users.name as userName',
                                    'categories.category_name'
                                ])
                                ->whereYear('articles.created_at', $year)
                                ->whereMonth('articles.created_at', $month)
                                ->where('articles.slug', $slug)
                                ->first();

        if (!$article) {
            return redirect()->route('dashboard');
        }

        $data['title']      =   $article->title;
        $data['article']    =   $article;
        $data['relateds']   =   Article::where('category_id', $article->category_id)
                                        ->where('id', '!=', $article->id)
                                        ->select([
                                            'articles.title', 'articles.thumbnail', 'articles.slug', 'articles.created_at'
                                        ])
                                        ->take(5)
                                        ->get();

        return view('Guest.Article.read', $data);
    }
}
