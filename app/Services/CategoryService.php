<?php

namespace App\Services;

use Str;

use App\Models\Category;

class CategoryService {
  public function getAllCategory() {
    try {
      $data = Category::orderBy('id')->get();

      return responseSuccess($data, 200, 'Berhasil ambil data kategori');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Gagal ambil data kategori');
    }
  }

  public function getCategoryById($id) {
    try {
      $data = Category::findOrFail($id);

      return responseSuccess($data, 200, 'Data kategori ditemukan');
    } catch (\Throwable $th) {
      return responseFailed(null, 404, 'Data kategori tidak ditemukan');
    }
  }

  public function getCategoryByCondition($column, $value) {
    try {
      $data = Category::where($column, $value)->orderBy('id')->get();

      return responseSuccess($data, 200, 'Berhasil ambil data kategori');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Gagal ambil data kategori');
    }
  }

  public function saveCategory($data) {
    try {
      $category                 = new Category;
      $category->category_name  = $data['category_name'];
      $category->status         = $data['status'];
      $category->slug           = Str::slug($data['category_name']);
      $category->save();

      return responseSuccess($category, 201, 'Kategori berhasil disimpan');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Kategori gagal disimpan');
    }
  }

  public function updateCategory($data) {
    try {
      $array  = array(
                  'category_name' =>  $data['category_name'],
                  'status'        =>  $data['status'],
                  'slug'          =>  Str::slug($data['category_name']),
                );

      $category = Category::findOrFail($data['category_id'])->update($array);

      return responseSuccess($category, 200, 'Kategori berhasil diupdate');
    } catch (\Throwable $th) {
      return responseFailed(null, 500, 'Kategori gagal diupdate');
    }
  }
}