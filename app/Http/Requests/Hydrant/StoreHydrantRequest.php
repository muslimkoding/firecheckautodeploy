<?php

namespace App\Http\Requests\Hydrant;

use Illuminate\Foundation\Http\FormRequest;

class StoreHydrantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // atau auth()->check() jika butuh login
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'number_hydrant' => 'required|string|max:50|unique:hydrants,number_hydrant',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            
            // Foreign keys
            'zone_id' => 'required|exists:zones,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
            'brand_id' => 'required|exists:brands,id',
            'hydrant_type_id' => 'required|exists:hydrant_types,id',
            'extinguisher_condition_id' => 'required|exists:extinguisher_conditions,id',
        ];
    }

    /**
     * prepare data for validation - transform ke UPPERCASE
     * di model User juga sudah ada mutator, saya buat lagi biar lebih aman
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'number_hydrant' => trim(strtoupper($this->number_hydrant)),
        ]);
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Number Hydrant
            'number_hydrant.required' => 'Nomor Hydrant wajib diisi',
            'number_hydrant.string' => 'Nomor Hydrant harus berupa teks',
            'number_hydrant.max' => 'Nomor Hydrant maksimal 50 karakter',
            'number_hydrant.unique' => 'Nomor Hydrant sudah digunakan',
            
            // Location
            'location.required' => 'Lokasi Hydrant wajib diisi',
            'location.string' => 'Lokasi harus berupa teks',
            'location.max' => 'Lokasi maksimal 255 karakter',
            
            // Description
            'description.string' => 'Deskripsi harus berupa teks',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            
            // Status
            'is_active.boolean' => 'Status aktif harus true atau false',
            
            // Foreign keys - Required
            'updated_by' => 'required|exists:users,id',
            'zone_id.required' => 'Zona wajib dipilih',
            'building_id.required' => 'Gedung wajib dipilih',
            'floor_id.required' => 'Lantai wajib dipilih',
            'brand_id.required' => 'Merek wajib dipilih',
            'hydrant_type_id.required' => 'Tipe Hydrant wajib dipilih',
            'extinguisher_condition_id.required' => 'Kondisi Hydrant wajib dipilih',
            
            // Foreign keys - Exists
            'zone_id.exists' => 'Zona yang dipilih tidak valid',
            'building_id.exists' => 'Gedung yang dipilih tidak valid',
            'floor_id.exists' => 'Lantai yang dipilih tidak valid',
            'brand_id.exists' => 'Merek yang dipilih tidak valid',
            'hydrant_type_id.exists' => 'Tipe Hydrant yang dipilih tidak valid',
            'extinguisher_condition_id.exists' => 'Kondisi Hydrant yang dipilih tidak valid',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'number_hydrant' => 'Nomor Hydrant',
            'location' => 'Lokasi',
            'description' => 'Deskripsi',
            'is_active' => 'Status Aktif',
            'zone_id' => 'Zona',
            'building_id' => 'Gedung',
            'floor_id' => 'Lantai',
            'brand_id' => 'Merek',
            'hydrant_type_id' => 'Tipe Hydrant',
            'extinguisher_condition_id' => 'Kondisi Hydrant',
        ];
    }
}
