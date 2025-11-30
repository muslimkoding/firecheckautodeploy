<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'date_birth' => 'nullable|date',
            'nip' => 'nullable|string|max:20|unique:users,nip',
            'employe_type_id' => 'nullable|exists:employee_types,id',
            'group_id' => 'nullable|exists:groups,id',
            'position_id' => 'nullable|exists:positions,id',
            'competency_id' => 'nullable|exists:competencies,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'nip.unique' => 'NIP sudah digunakan',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
