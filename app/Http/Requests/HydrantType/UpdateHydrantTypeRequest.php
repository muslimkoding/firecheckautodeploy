<?php

namespace App\Http\Requests\HydrantType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateHydrantTypeRequest extends FormRequest
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
        $hydrantTypeId = $this->route('hydrant_type')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('hydrant_types')->ignore($hydrantTypeId)],
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
            'name.required' => 'Nama tipe Hydrant wajib diisi',
            'name.min' => 'Nama tipe Hydrant minimal 3 karakter',
            'name.max' => 'Nama tipe Hydrant maksimal 255 karakter',
            'description.min' => 'Nama tipe Hydrant minimal 3 karakter',
            'description.max' => 'Nama tipe Hydrant minimal 255 karakter',
        ];
    }
}
