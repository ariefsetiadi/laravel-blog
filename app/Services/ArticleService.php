<?php

namespace App\Services;

use Auth;
use Carbon\Carbon;
use File;
use Str;

use App\Models\User;
use App\Models\Category;
use App\Models\Article;

class ArticleService {
  public function getAllArticleWithUserAndCategory() {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
                      ->join('categories', 'categories.id', 'articles.category_id')
                      ->select([
                          'articles.id', 'articles.title', 'articles.status', 'articles.slug', 'articles.updated_at',
                          'users.id as userId', 'users.name as userName',
                          'categories.id as catId', 'categories.category_name'
                      ])->where(function ($query) {
                        // Menampilkan artikel yang ditulis oleh user yang sedang login
                        $query->where('articles.created_by', Auth::id())
                              ->orWhere(function ($subQuery) {
                                  // Menampilkan artikel yang ditulis oleh user lain yang tidak berstatus Draft
                                  $subQuery->where('articles.created_by', '!=', Auth::id())
                                            ->where('articles.status', '!=', 0)
                                            ->where('articles.status', '!=', 2);
                        });
                      })->orderBy('articles.created_at', 'DESC')
                      ->orderBy('articles.status', 'ASC')
                      ->get();

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function getAllArticleWithUserAndCategoryByAuth() {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
                      ->join('categories', 'categories.id', 'articles.category_id')
                      ->select([
                          'articles.id', 'articles.title', 'articles.status', 'articles.slug', 'articles.updated_at',
                          'users.id as userId', 'users.name as userName',
                          'categories.id as catId', 'categories.category_name'
                      ])->where('articles.created_by', Auth::user()->id)
                      ->orderBy('articles.created_at', 'DESC')
                      ->get();

      return responseSuccess($data, 200, 'Berhasil ambil data artikel');
    } catch (\Throwable $th) {
      return responseSuccess(null, 500, 'Gagal ambil data artikel');
    }
  }

  public function getArticleById($id) {
    try {
      $data = Article::where('id', $id)
                      ->where('created_by', Auth::user()->id)
                      ->first();

      if ($data == null) {
        return responseFailed(null, 404, 'Data artikel tidak ditemukan');
      } else {
        return responseSuccess($data, 200, 'Data artikel ditemukan');
      }
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Terjadi kesalahan');
    }
  }

  public function saveArticle($data) {
    try {
      // Upload Thumbnail
      if (isset($data['thumbnail']) && is_file($data['thumbnail'])) {
        $path       = 'uploads/articles/thumbnails';
        $thumbnail  = $data['thumbnail'];

        // Create folder if haven't
        if (!File::exists($path)) {
          File::makeDirectory($path, $mode = 0775, true, true);
        }

        $thumbnail_name = 'thumbnail-' . Str::slug($data['title']) . '.' . $thumbnail->getClientOriginalExtension();
        $thumbnail->move(public_path($path), $thumbnail_name);
      }

      $status;
      $publishedBy = NULL;
      $publishedAt = NULL;

      if ($data['action'] === 'Draft') {
        $status = 0;
      } else if ($data['action'] === 'Submit') {
        if (Auth::user()->can('Review Artikel')) {
          $status = 3;
          $publishedBy = Auth::user()->id;
          $publishedAt = Carbon::now();
        } else {
          $status = 1;
        }
      }

      $article                = new Article;
      $article->category_id   = $data['category_id'];
      $article->title         = $data['title'];
      $article->thumbnail     = $thumbnail_name;
      $article->content       = $data['content'];
      $article->status        = $status;
      $article->slug          = Str::slug($data['title']);
      $article->created_by    = Auth::user()->id;
      $article->updated_by    = Auth::user()->id;
      $article->published_by  = $publishedBy;
      $article->published_at  = $publishedAt;
      $article->save();

      return responseSuccess($article, 201, 'Artikel berhasil disimpan');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Artikel gagal disimpan');
    }
  }

  public function updateArticle($data) {
    try {
      $article        = Article::where('id', $data['article_id'])
                                ->where('created_by', Auth::user()->id)
                                ->where('status', '!=', 1)
                                ->first();

      if (!$article) {
        return responseFailed(null, 404, 'Data artikel tidak ditemukan');
      } else {
        // Validasi artikel publish tidak bisa menjadi draft
        if ($article->status == 3 && $data['action'] === 'Draft') {
          return responseFailed(null, 406, 'Artikel yang sudah di Publish tidak dapat menjadi Draft');
        }

        $thumbnail_name = $article->thumbnail;

        // Upload Thumbnail
        if (isset($data['thumbnail']) && is_file($data['thumbnail'])) {
          $path       = 'uploads/articles/thumbnails';
          $thumbnail  = $data['thumbnail'];

          // Create folder if haven't
          if (!File::exists($path)) {
              File::makeDirectory($path, $mode = 0775, true, true);
          }

          // Delete thumbnail if any upload other thumbnail
          if ($article->thumbnail != NULL) {
            File::delete('uploads/articles/thumbnails/' . $article->thumbnail);
          }

          $thumbnail_name = 'thumbnail-' . Str::slug($data['title']) . '.' . $thumbnail->getClientOriginalExtension();
          $thumbnail->move(public_path($path), $thumbnail_name);
        }

        $status;
        $publishedBy = NULL;
        $publishedAt = NULL;

        if ($data['action'] === 'Draft') {
          $status = 0;
        } else if ($data['action'] === 'Submit') {
          if (Auth::user()->can('Review Artikel')) {
            $status = 3;
            $publishedBy = Auth::user()->id;
            $publishedAt = Carbon::now();
          } else {
            $status = 1;
          }
        }

        $array  = array(
                    'category_id'   =>  $data['category_id'],
                    'title'         =>  $data['title'],
                    'thumbnail'     =>  $thumbnail_name,
                    'content'       =>  $data['content'],
                    'status'        =>  $status,
                    'slug'          =>  Str::slug($data['title']),
                    'updated_by'    =>  Auth::user()->id,
                    'published_by'  =>  $publishedBy,
                    'published_at'  =>  $publishedAt,
                  );

        Article::findOrFail($data['article_id'])->update($array);

        return responseSuccess($article, 200, 'Artikel berhasil diupdate');
      }
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Artikel gagal diupdate');
    }
  }

  public function getReviewArticle($id) {
    try {
      $data = Article::join('users', 'users.id', 'articles.created_by')
                      ->join('categories', 'categories.id', 'articles.category_id')
                      ->select([
                        'articles.id', 'articles.title', 'articles.thumbnail', 'articles.content', 'articles.status', 'articles.notes',
                        'users.name', 'categories.category_name',
                      ])
                      ->where('articles.id', $id)
                      ->where('articles.status', 1)
                      ->first();

      if ($data == null) {
        return responseFailed(null, 404, 'Data artikel tidak ditemukan');
      } else {
        return responseSuccess($data, 200, 'Data artikel ditemukan');
      }
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Terjadi kesalahan');
    }
  }

  public function updateReviewArticle($data) {
    try {
      $article        = Article::where('id', $data['article_id'])
                                ->where('status', 1)
                                ->first();

      if (!$article) {
        return responseFailed(null, 404, 'Data artikel tidak ditemukan');
      } else {
        $status;
        $notes = NULL;
        $publishedBy = NULL;
        $publishedAt = NULL;

        if ($data['action'] === 'Revise') {
          $status = 2;
          $notes = $data['notes'];
        } else if ($data['action'] === 'Publish') {
          $status = 3;
          $publishedBy = Auth::user()->id;
          $publishedAt = Carbon::now();
        }

        $array  = array(
                    'status'        =>  $status,
                    'notes'         =>  $notes,
                    'updated_by'    =>  Auth::user()->id,
                    'published_by'  =>  $publishedBy,
                    'published_at'  =>  $publishedAt,
                  );

        Article::findOrFail($data['article_id'])->update($array);

        return responseSuccess($article, 200, 'Artikel berhasil direview');
      }
    } catch (\Throwable $th) {
      // return responseFailed(null, 500, 'Artikel gagal direview');
      return responseFailed(null, 500, $th);
    }
  }

  public function uploadImageTinyMCE($data) {
    try {
      $filename = 'article_' . round(microtime(true) * 1000) . '.' . $data->file('file')->getClientOriginalExtension();
      $path     = $data->file('file')->storeAs('uploads/articles', $filename, 'public');

      $file = [
        'location'  =>  "/storage/$path",
        'filename'  =>  $filename,
      ];

      return responseSuccess($file, 200, 'Gambar berhasil diupload');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Gambar gagal diupload');
    }
  }

  public function deleteImageTinyMCE($data) {
    try {
      $path = public_path($data->imagePath);

      if (File::exists($path)) {
        File::delete($path);
      }

      return responseSuccess(null, 200, 'Gambar berhasil dihapus');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Gambar gagal dihapus');
    }
  }
}