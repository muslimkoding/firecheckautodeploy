<?php

namespace App\Http\Requests\HydrantDoor;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreHydrantDoorRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255|unique:hydrant_doors,name',
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
            'name.required' => 'Nama kondisi pintu hydrant wajib di isi',
            'name.min' => 'Nama kondisi pintu hydrant minimal 3 karakter',
            'name.max' => 'Nama kondisi pintu hydrant maksimal 255 karakter',
            'name.unique' => 'Nama kondisi pintu hydrant sudah ada',
            'description.min' => 'Nama kondisi pintu hydrant minimal 3 karakter',
            'description.max' => 'Nama kondisi pintu hydrant minimal 255 karakter',
        ];
    }
}
