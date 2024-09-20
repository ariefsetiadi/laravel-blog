<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteConfigRequest extends FormRequest
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
            'name'          =>  'required|max:50',
            'icon'          =>  'max:2048',
            'icon.*'        =>  'mimes:jpg,jpeg,png,webp,ico,svg',
            'description'   =>  'nullable|string|max:200',
            'phone'         =>  'required|array|min:1',
            'phone.*'       =>  'required|min:9|max:15|regex:/^[0-9]*$/',
            'email'         =>  'required|email',
            'social_media'  =>  'array',
            'facebook'      =>  'nullable|url',
            'instagram'     =>  'nullable|url',
            'linkedin'      =>  'nullable|url',
            'twitter'       =>  'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     =>  'Nama Website wajib diisi',
            'name.max'          =>  'Nama Website maksimal 50 karakter',
            'icon.max'          =>  'Icon / Logo maksimal 2 MB',
            'icon.mimes'        =>  'Icon / Logo berformat .jps, .jpeg, .png, .webp, .ico, atau .svg',
            'description.max'   =>  'Deskripsi maksimal 200 karakter',
            'phone.required'    =>  'Telepon wajib diisi minimal satu nomor',
            'phone.min'         =>  'Telepon wajib diisi minimal satu nomor',
            'phone.*.required'  =>  'Telepon wajib diisi',
            'phone.*.min'       =>  'Telepon minimal 9 angka',
            'phone.*.max'       =>  'Telepon maksimal 15 angka',
            'phone.*.regex'     =>  'Format Telepon wajib angka 0 - 9',
            'email.required'    =>  'Email wajib diisi',
            'email.email'       =>  'Email tidak valid',
            'facebook.url'      =>  'URL Facebook tidak valid',
            'instagram.url'     =>  'URL Instagram tidak valid',
            'linkedin.url'      =>  'URL LinkedIn tidak valid',
            'twitter.url'      =>  'URL Twitter / X tidak valid',
        ];
    }
}
