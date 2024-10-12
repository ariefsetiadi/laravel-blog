<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $query  =   Article::join('users', 'users.id', 'articles.created_by')
                            ->join('categories', 'categories.id', 'articles.category_id')
                            ->where('articles.status', 3)
                            ->select([
                                'articles.title', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                                'users.name as userName',
                                'categories.category_name'
                            ])->orderBy('articles.created_at', 'desc')
                            ->take(10)
                            ->get();

        $data['title']      =   'Home';
        $data['banner']     =   $query->slice(0, 3);
        $data['first']      =   $query->slice(3, 1);
        $data['second']     =   $query->slice(4, 3);
        $data['third']      =   $query->slice(7, 3);
        $data['trendings']  =   Article::join('users', 'users.id', 'articles.created_by')
                                        ->join('page_views', 'page_views.article_id', 'articles.id')
                                        ->where('articles.status', true)
                                        ->select([
                                            'articles.title', 'articles.slug', 'articles.created_at',
                                            'users.name',
                                        ])
                                        ->selectRaw('COUNT(page_views.id) as totalView')
                                        ->groupBy([
                                            'articles.title',
                                            'articles.slug',
                                            'articles.created_at',
                                            'users.name',
                                        ])
                                        ->orderBy('totalView', 'desc')
                                        ->orderBy('articles.created_at', 'desc')
                                        ->take(5)
                                        ->get();

        return view('Guest.dashboard', $data);
    }

    public function viewContact()
    {
        $data['title']  =   'Contact';

        return view('Guest.contact', $data);
    }
}
