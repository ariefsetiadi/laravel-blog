<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name'          =>  'required|max:255|unique:roles,name,' . $this->role_id,
            'permission'    =>  'required|array|exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         =>  'Nama Role wajib diisi',
            'name.max'              =>  'Nama Role maksimal 255 karakter',
            'name.unique'           =>  'Nama Role sudah digunakan',
            'permission.required'   =>  'Permission wajib dipilih minimal satu',
            'permission.array'      =>  'Permission wajib dipilih minimal satu',
            'permission.exists'     =>  'Permission tidak valid',
        ];
    }
}
