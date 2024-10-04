<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'oldPassword'           =>  'required',
            'password'              =>  'required|min:6|max:20',
            'password_confirmation' =>  'required|min:6|max:20|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'oldPassword.required'              =>  'Password Saat Ini wajib diisi',
            'password.required'                 =>  'Password Baru wajib diisi',
            'password.min'                      =>  'Password Baru minimal 6 karakter',
            'password.max'                      =>  'Password Baru maksimal 20 karakter',
            'password_confirmation.required'    =>  'Ulangi Password Baru wajib diisi',
            'password_confirmation.min'         =>  'Ulangi Password Baru minimal 6 karakter',
            'password_confirmation.max'         =>  'Ulangi Password Baru maksimal 20 karakter',
            'password_confirmation.same'        =>  'Ulangi Password Baru harus sama dengan Password Baru',
        ];
    }
}
