<?php

namespace App\Http\Requests\HydrantMainValve;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreHydrantMainValveRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255|unique:hydrant_main_valves,name',
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
            'name.required' => 'Nama kondisi main valve hydrant wajib di isi',
            'name.min' => 'Nama kondisi main valve hydrant minimal 3 karakter',
            'name.max' => 'Nama kondisi main valve hydrant maksimal 255 karakter',
            'name.unique' => 'Nama kondisi main valve hydrant sudah ada',
            'description.min' => 'Nama kondisi main valve hydrant minimal 3 karakter',
            'description.max' => 'Nama kondisi main valve hydrant minimal 255 karakter',
        ];
    }
}
