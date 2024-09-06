<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password'              =>  'required|min:6|max:20',
            'password_confirmation' =>  'required|min:6|max:20|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required'                 =>  'Password wajib diisi',
            'password.min'                      =>  'Password minimal 6 karakter',
            'password.max'                      =>  'Password maksimal 20 karakter',
            'password_confirmation.required'    =>  'Konfirmasi Password wajib diisi',
            'password_confirmation.min'         =>  'Konfirmasi Password minimal 6 karakter',
            'password_confirmation.max'         =>  'Konfirmasi Password maksimal 20 karakter',
            'password_confirmation.same'        =>  'Konfirmasi Password harus sama dengan Password',
        ];
    }
}
