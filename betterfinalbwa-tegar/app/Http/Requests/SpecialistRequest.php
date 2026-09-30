<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // semua user boleh pakai request ini
        // return false; // Semua ditolak → 403 Forbidden
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('specialist');

        return [
            'name' => 'required|string|unique:specialists,name,' . $id, // name harus unik di tabel specialists kolom name, kecuali untuk record dengan ID yang sedang diupdate (untuk update)
            'photo' => $this->isMethod('post') ? 'required|image|max:2048' : 'sometimes|image|max:2048', // maks 2 MB
            'about' => 'required|string',
            'price' => 'required|numeric|min:0',
        ];
    }
}
