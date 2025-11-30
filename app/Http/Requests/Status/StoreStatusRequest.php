<?php

namespace App\Http\Requests\Status;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreStatusRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255|unique:statuses,name',
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
            'name.required' => 'Nama status wajib di isi',
            'name.min' => 'Nama status minimal 3 karakter',
            'name.max' => 'Nama status maksimal 255 karakter',
            'name.unique' => 'Nama status sudah ada',
            'description.min' => 'Nama status minimal 3 karakter',
            'description.max' => 'Nama status minimal 255 karakter',
        ];
    }
}
