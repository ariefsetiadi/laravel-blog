<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->article_id != NULL) {
            $validate = 'nullable';
        } else {
            $validate = 'required';
        }

        return [
            'title'         =>  'required|max:255|unique:articles,title,' . $this->article_id,
            'thumbnail'     =>  $validate . '|max:2048',
            'thumbnail.*'   =>  'mimes:jpg,jpeg,png,webp',
            'category_id'   =>  'required|exists:categories,id',
            'content'       =>  'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        =>  'Judul Artikel wajib diisi',
            'title.max'             =>  'Judul Artikel maksimal 255 karakter',
            'title.unique'          =>  'Judul Artikel sudah digunakan',
            'thumbnail.required'    =>  'Thumbnail wajib diupload',
            'thumbnail.max'         =>  'Thumbnail maksimal 2 MB',
            'thumbnail.mimes'       =>  'Thumbnail wajib jpg, jpeg, png, atau webp',
            'category_id.required'  =>  'Kategori wajib dipilih',
            'category_id.exists'    =>  'Kategori tidak valid',
            'content.required'      =>  'Konten wajib diisi',
        ];
    }
}
