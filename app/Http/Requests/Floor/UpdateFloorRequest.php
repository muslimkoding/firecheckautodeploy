<?php

namespace App\Http\Requests\Floor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateFloorRequest extends FormRequest
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
        $floorId = $this->route('floor')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('floors')->ignore($floorId)],
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
            'name.required' => 'Nama lantai wajib diisi',
            'name.min' => 'Nama lantai minimal 3 karakter',
            'name.max' => 'Nama lantai maksimal 255 karakter',
            'description.min' => 'Nama lantai minimal 3 karakter',
            'description.max' => 'Nama lantai minimal 255 karakter',
        ];
    }
}