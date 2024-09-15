<?php

namespace App\Services;

use App\Models\PageView;

class PageViewService
{
  public function trackPageView($articleId)
  {
    $sessionId = request()->cookie('session_id');
    PageView::create([
        'session_id'  =>  $sessionId,
        'article_id'  =>  $articleId,
        'view_at'     =>  now(),
    ]);
  }
}