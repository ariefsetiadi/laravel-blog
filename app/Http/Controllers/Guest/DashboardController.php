<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Guest\ArticleService;

class DashboardController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
    ) { }

    public function index()
    {
        $articles   =   $this->articleService->getArticleForDashboard();
        $trendings  =   $this->articleService->getTrendingArticleForDashboard();

        $data['title']      =   'Home';
        $data['banner']     =   $articles['data']->slice(0, 3);
        $data['first']      =   $articles['data']->slice(3, 1);
        $data['second']     =   $articles['data']->slice(4, 3);
        $data['third']      =   $articles['data']->slice(7, 3);
        $data['trendings']  =   $trendings['data'];

        return view('Guest.dashboard', $data);
    }

    public function viewContact()
    {
        $data['title']  =   'Contact';

        return view('Guest.contact', $data);
    }
}
