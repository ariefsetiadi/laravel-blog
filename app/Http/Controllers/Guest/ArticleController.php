<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\PageViewService;
use App\Services\Guest\ArticleService;

use App\Models\Article;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        protected PageViewService $pageViewService,
    ) { }

    public function index()
    {
        $articles   =   $this->articleService->getArticlePagination();
        $populars   =   $this->articleService->getArticlePopular();

        $data['title']      =   'Semua Artikel';
        $data['articles']   =   $articles['data'];
        $data['populars']   =   $populars['data'];

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

        $article    =   $this->articleService->readArticle($year, $month, $slug);
        $relateds   =   $this->articleService->articleRelateds($article['data']->category_id, $article['data']->id);

        $data['title']      =   $article['data']->title;
        $data['article']    =   $article['data'];
        $data['relateds']   =   $relateds['data'];

        return view('Guest.Article.read', $data);
    }
}
