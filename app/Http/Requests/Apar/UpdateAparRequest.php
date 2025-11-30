<?php

namespace App\Http\Requests\Apar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAparRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $aparId = $this->route('apar'); // atau 'apar' sesuai route parameter
        
        return [
            'number_apar' => [
                'required',
                'string',
                'max:50',
                Rule::unique('apars')->ignore($aparId)
            ],
            'location' => 'required|string|max:255',
            'weight_of_extinguiser' => 'required|numeric|min:0.1|max:1000',
            'description' => 'nullable|string|max:1000',
            'expired_date' => 'required|date|after_or_equal:today',
            'is_active' => 'boolean',
            
            // Foreign keys
            'zone_id' => 'required|exists:zones,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
            'brand_id' => 'required|exists:brands,id',
            'apar_type_id' => 'required|exists:apar_types,id',
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
            'number_apar' => trim(strtoupper($this->number_apar)),
        ]);
    }

    public function messages(): array
    {
        return [
            'number_apar.required' => 'Nomor APAR wajib diisi',
            'number_apar.string' => 'Nomor APAR harus berupa teks',
            'number_apar.max' => 'Nomor APAR maksimal 50 karakter',
            'number_apar.unique' => 'Nomor APAR sudah digunakan',
            
            'location.required' => 'Lokasi APAR wajib diisi',
            'location.string' => 'Lokasi harus berupa teks',
            'location.max' => 'Lokasi maksimal 255 karakter',
            
            'weight_of_extinguiser.required' => 'Berat APAR wajib diisi',
            'weight_of_extinguiser.numeric' => 'Berat harus berupa angka',
            'weight_of_extinguiser.min' => 'Berat minimal 0.1 kg',
            'weight_of_extinguiser.max' => 'Berat maksimal 1000 kg',
            
            'description.string' => 'Deskripsi harus berupa teks',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            
            'expired_date.required' => 'Tanggal expired wajib diisi',
            'expired_date.date' => 'Format tanggal expired tidak valid',
            'expired_date.after_or_equal' => 'Tanggal expired tidak boleh sebelum hari ini',
            
            'is_active.boolean' => 'Status aktif harus true atau false',
            
            'zone_id.required' => 'Zona wajib dipilih',
            'building_id.required' => 'Gedung wajib dipilih',
            'floor_id.required' => 'Lantai wajib dipilih',
            'brand_id.required' => 'Merek wajib dipilih',
            'apar_type_id.required' => 'Tipe APAR wajib dipilih',
            'extinguisher_condition_id.required' => 'Kondisi APAR wajib dipilih',
            
            'zone_id.exists' => 'Zona yang dipilih tidak valid',
            'building_id.exists' => 'Gedung yang dipilih tidak valid',
            'floor_id.exists' => 'Lantai yang dipilih tidak valid',
            'brand_id.exists' => 'Merek yang dipilih tidak valid',
            'apar_type_id.exists' => 'Tipe APAR yang dipilih tidak valid',
            'extinguisher_condition_id.exists' => 'Kondisi APAR yang dipilih tidak valid',
        ];
    }

    public function attributes(): array
    {
        return [
            'number_apar' => 'Nomor APAR',
            'location' => 'Lokasi',
            'weight_of_extinguiser' => 'Berat APAR',
            'description' => 'Deskripsi',
            'expired_date' => 'Tanggal Expired',
            'is_active' => 'Status Aktif',
            'zone_id' => 'Zona',
            'building_id' => 'Gedung',
            'floor_id' => 'Lantai',
            'brand_id' => 'Merek',
            'apar_type_id' => 'Tipe APAR',
            'extinguisher_condition_id' => 'Kondisi APAR',
        ];
    }
}
