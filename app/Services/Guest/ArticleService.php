<?php

namespace App\Services\Guest;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;

class ArticleService {
  public function getArticleForDashboard() {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
                      ->join('categories', 'categories.id', 'articles.category_id')
                      ->where('articles.status', 3)
                      ->select([
                          'articles.title', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                          'users.name as userName',
                          'categories.category_name'
                      ])->orderBy('articles.created_at', 'desc')
                      ->take(10)
                      ->get();

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function getTrendingArticleForDashboard() {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
                      ->join('page_views', 'page_views.article_id', 'articles.id')
                      ->where('articles.status', 3)
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

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function getArticlePagination() {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
                      ->join('categories', 'categories.id', 'articles.category_id')
                      ->where('articles.status', 3)
                      ->select([
                          'articles.title', 'articles.thumbnail', 'articles.content', 'articles.slug', 'articles.created_at',
                          'users.name as userName',
                          'categories.category_name'
                      ])->orderBy('articles.created_at', 'desc')
                      ->paginate(6);

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function getArticlePopular() {
    try {
      $data = Article::join('page_views', 'page_views.article_id', 'articles.id')
                      ->where('articles.status', 3)
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

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function readArticle($year, $month, $slug) {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
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

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function articleRelateds($categoryId, $id) {
    try {
      $data = Article::where('category_id', $categoryId)
                      ->where('status', 3)
                      ->where('id', '!=', $id)
                      ->select([
                          'articles.title', 'articles.thumbnail', 'articles.slug', 'articles.created_at'
                      ])
                      ->take(5)
                      ->get();

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }
}