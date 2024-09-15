<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name'      =>  'required|min:3|max:255|regex:/^[a-zA-Z0-9\s.,]+$/',
            'email'     =>  'required|email|max:255|unique:users,email,' . $this->user_id,
            'role'      =>  'required|exists:roles,id',
            'is_active' =>  'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         =>  'Nama Lengkap wajib diisi',
            'name.min'              =>  'Nama Lengkap minimal 3 karakter',
            'name.max'              =>  'Nama Lengkap maksimal 255 karakter',
            'name.regex'            =>  'Nama Lengkap hanya boleh huruf, angka, spasi, titik, dan koma saja',
            'email.required'        =>  'Email wajib diisi',
            'email.email'           =>  'Email tidak valid',
            'email.max'             =>  'Email maksimal 255 karakter',
            'email.unique'          =>  'Email sudah digunakan',
            'role.required'         =>  'Role wajib dipilih',
            'role.exists'           =>  'Role tidak valid',
            'is_active.required'    =>  'Status wajib dipilih',
            'is_active.in'          =>  'Status tidak valid',
        ];
    }
}
