<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\PageViewService;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\PageView;
use App\Models\Session;

class ArticleController extends Controller
{
    protected $pageViewService;

    public function __construct(PageViewService $pageViewService)
    {
        $this->pageViewService  =   $pageViewService;
    }

    public function index()
    {
        $articles   =   Article::join('users', 'users.id', 'articles.created_by')
                                ->join('categories', 'categories.id', 'articles.category_id')
                                ->where('articles.status', 3)
                                ->select([
                                    'articles.title', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                                    'users.name as userName',
                                    'categories.category_name'
                                ])->orderBy('articles.created_at', 'desc')
                                ->paginate(6);

        $data['title']      =   'Semua Artikel';
        $data['articles']   =   $articles;
        $data['populars']   =   Article::join('page_views', 'page_views.article_id', 'articles.id')
                                        ->where('articles.status', true)
                                        ->select([
                                            'articles.title', 'articles.thumbnail', 'articles.slug', 'articles.created_at',
                                        ])
                                        ->selectRaw('COUNT(page_views.id) as totalView')
                                        ->groupBy([
                                            'articles.title',
                                            'articles.thumbnail',
                                            'articles.slug',
                                            'articles.created_at',
                                        ])
                                        ->orderBy('totalView', 'desc')
                                        ->orderBy('articles.created_at', 'desc')
                                        ->take(5)
                                        ->get();

        return view('Guest.Article.index', $data);
    }

    public function read($year, $month, $slug)
    {
        $query  =   Article::whereYear('articles.created_at', $year)
                            ->whereMonth('articles.created_at', $month)
                            ->where('articles.status', 3)
                            ->where('articles.slug', $slug)
                            ->first();

        if (!$query) {
            return redirect()->route('dashboard');
        }

        // Insert or update view Article based on session
        $this->pageViewService->trackPageView($query->id);

        $article    =   Article::join('users', 'users.id', 'articles.created_by')
                                ->join('categories', 'categories.id', 'articles.category_id')
                                ->leftJoin('page_views', 'page_views.article_id', 'articles.id')
                                ->select([
                                    'articles.id', 'articles.title', 'articles.category_id', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                                    'users.name as userName',
                                    'categories.category_name'
                                ])
                                ->selectRaw('COUNT(page_views.id) as totalView')
                                ->whereYear('articles.created_at', $year)
                                ->whereMonth('articles.created_at', $month)
                                ->where('articles.status', 3)
                                ->where('articles.slug', $slug)
                                ->groupBy([
                                    'articles.id',
                                    'articles.title',
                                    'articles.category_id',
                                    'articles.thumbnail',
                                    'articles.content',
                                    'articles.slug',
                                    'articles.created_at',
                                    'users.name',
                                    'categories.category_name'
                                ])
                                ->first();

        $data['title']      =   $article->title;
        $data['article']    =   $article;
        $data['relateds']   =   Article::where('category_id', $article->category_id)
                                        ->where('status', 3)
                                        ->where('id', '!=', $article->id)
                                        ->select([
                                            'articles.title', 'articles.thumbnail', 'articles.slug', 'articles.created_at'
                                        ])
                                        ->take(5)
                                        ->get();

        return view('Guest.Article.read', $data);
    }
}
