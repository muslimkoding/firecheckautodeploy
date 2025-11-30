<?php

namespace App\Http\Requests\HydrantNozzle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreHydrantNozzleRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255|unique:hydrant_nozzles,name',
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
            'name.required' => 'Nama kondisi nozzle Hydrant wajib di isi',
            'name.min' => 'Nama kondisi nozzle Hydrant minimal 3 karakter',
            'name.max' => 'Nama kondisi nozzle Hydrant maksimal 255 karakter',
            'name.unique' => 'Nama kondisi nozzle Hydrant sudah ada',
            'description.min' => 'Nama kondisi nozzle Hydrant minimal 3 karakter',
            'description.max' => 'Nama kondisi nozzle Hydrant minimal 255 karakter',
        ];
    }
}
