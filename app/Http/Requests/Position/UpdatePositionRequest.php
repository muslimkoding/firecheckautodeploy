<?php

namespace App\Http\Requests\Position;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
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
        $positionId = $this->route('position')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('positions')->ignore($positionId)],
            'slug' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|min:3|max:255',
        ];
    }

     /**
     * custom message validation form
     */
    public function messages()
    {
        return [
            'name.required' => 'Nama jabatan wajib diisi',
            'name.min' => 'Nama jabatan minimal 3 karakter',
            'name.max' => 'Nama jabatan maksimal 255 karakter',
            'description.min' => 'Nama jabatan minimal 3 karakter',
            'description.max' => 'Nama jabatan minimal 255 karakter',
        ];
    }
}