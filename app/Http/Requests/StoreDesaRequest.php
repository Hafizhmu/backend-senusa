<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesaRequest extends FormRequest
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
        if ($this->has('nama_desa')) {
            $this->merge([
                'nama_desa' => 'Desa ' . $this->nama_desa,
            ]);
        }
        if (request()->isMethod('post')) {
            return [
                'nama_desa' => [
                    'required',
                    'string',
                    'max:255',
                    // Aturan unik dengan klausa where
                    'unique:desas,nama_desa,NULL,id,id_kecamatan,' . $this->id_kecamatan,
                ],
                'nama_kades' => 'required|string|max:255',
                'id_kecamatan' => 'required|integer|exists:kecamatans,id',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|string|max:15',
            ];
        } else {
            return [
                'nama_desa' => [
                    'required',
                    'string',
                    'max:255',
                    // Aturan unik dengan klausa where
                    'unique:desas,nama_desa,NULL,id,id_kecamatan,' . $this->id_kecamatan,
                ],
                'nama_kades' => 'required|string|max:255',
                'id_kecamatan' => 'required|integer|exists:kecamatans,id',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|string|max:15',
            ];
        }
    }
}
