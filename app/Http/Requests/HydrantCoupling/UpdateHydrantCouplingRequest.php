<?php

namespace App\Http\Requests\HydrantCoupling;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHydrantCouplingRequest extends FormRequest
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
        $hydrantCouplingId = $this->route('hydrant_coupling')->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('hydrant_couplings')->ignore($hydrantCouplingId)],
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
            'name.required' => 'Nama kondisi coupling hydrant wajib diisi',
            'name.min' => 'Nama kondisi coupling hydrant minimal 3 karakter',
            'name.max' => 'Nama kondisi coupling hydrant maksimal 255 karakter',
            'description.min' => 'Nama kondisi coupling hydrant minimal 3 karakter',
            'description.max' => 'Nama kondisi coupling hydrant minimal 255 karakter',
        ];
    }
}

