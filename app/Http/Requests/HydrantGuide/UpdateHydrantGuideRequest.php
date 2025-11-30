<?php

namespace App\Http\Requests\HydrantGuide;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateHydrantGuideRequest extends FormRequest
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
        $hydrantGuideId = $this->route('hydrant_guide')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('hydrant_guides')->ignore($hydrantGuideId)],
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
            'name.required' => 'Nama petunjuk penggunaan hydrant wajib diisi',
            'name.min' => 'Nama petunjuk penggunaan hydrant minimal 3 karakter',
            'name.max' => 'Nama petunjuk penggunaan hydrant maksimal 255 karakter',
            'description.min' => 'Nama petunjuk penggunaan hydrant minimal 3 karakter',
            'description.max' => 'Nama petunjuk penggunaan hydrant minimal 255 karakter',
        ];
    }
}
