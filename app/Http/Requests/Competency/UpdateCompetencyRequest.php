<?php

namespace App\Http\Requests\Competency;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompetencyRequest extends FormRequest
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
        $competencyId = $this->route('competency')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('competencies')->ignore($competencyId)],
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
            'name.required' => 'Nama kompetensi wajib diisi',
            'name.min' => 'Nama kompetensi minimal 3 karakter',
            'name.max' => 'Nama kompetensi maksimal 255 karakter',
            'description.min' => 'Nama kompetensi minimal 3 karakter',
            'description.max' => 'Nama kompetensi minimal 255 karakter',
        ];
    }
}
