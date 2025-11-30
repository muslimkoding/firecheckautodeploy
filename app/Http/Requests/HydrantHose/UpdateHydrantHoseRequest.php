<?php

namespace App\Http\Requests\HydrantHose;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHydrantHoseRequest extends FormRequest
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
        $hydrantHoseId = $this->route('hydrant_hose')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('hydrant_hoses')->ignore($hydrantHoseId)],
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
            'name.required' => 'Nama kondisi selang Hydrant wajib diisi',
            'name.min' => 'Nama kondisi selang Hydrant minimal 3 karakter',
            'name.max' => 'Nama kondisi selang Hydrant maksimal 255 karakter',
            'description.min' => 'Nama kondisi selang Hydrant minimal 3 karakter',
            'description.max' => 'Nama kondisi selang Hydrant minimal 255 karakter',
        ];
    }
}
