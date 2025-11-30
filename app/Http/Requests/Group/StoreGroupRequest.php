<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * membuat slug berdasarkan input name
     */

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:groups,name',
            'slug' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|min:3|max:255'
        ];
    }

    /**
     * custom message validation form
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama group wajib di isi',
            'name.min' => 'Nama group minimal 3 karakter',
            'name.max' => 'Nama group maksimal 255 karakter',
            'name.unique' => 'Nama group sudah ada',
            'description.min' => 'Nama group minimal 3 karakter',
            'description.max' => 'Nama group minimal 255 karakter',
        ];
    }
}
