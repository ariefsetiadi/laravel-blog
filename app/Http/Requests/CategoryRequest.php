<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        return [
            'category_name' =>  'required|min:3|max:255|unique:categories,category_name,' . $this->category_id,
            'status'        =>  'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required'    =>  'Nama Kategori wajib diisi',
            'category_name.min'         =>  'Nama Kategori minimal 3 karakter',
            'category_name.max'         =>  'Nama Kategori maksimal 255 karakter',
            'category_name.unique'      =>  'Nama Kategori sudah digunakan',
            'status.required'           =>  'Status wajib dipilih',
            'status.in'                 =>  'Status tidak valid',
        ];
    }
}
