<?php

namespace App\Http\Requests\HydrantSafetyMarking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateHydrantSafetyMarkingRequest extends FormRequest
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
        $hydrantSafetMarkingId = $this->route('hydrant_safety_marking')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('hydrant_safety_markings')->ignore($hydrantSafetMarkingId)],
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
            'name.required' => 'Nama kondisi safety markings wajib diisi',
            'name.min' => 'Nama kondisi safety markings minimal 3 karakter',
            'name.max' => 'Nama kondisi safety markings maksimal 255 karakter',
            'description.min' => 'Nama kondisi safety markings minimal 3 karakter',
            'description.max' => 'Nama kondisi safety markings minimal 255 karakter',
        ];
    }
}
