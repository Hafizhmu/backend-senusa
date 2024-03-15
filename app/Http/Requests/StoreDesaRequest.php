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
        if (request()->isMethod('post')) {
            return [
                'nama_desa' => 'required|string',
                'nama_kades' => 'required|string',
                'id_kecamatan' => 'required|integer|exists:kecamatans,id',
                'id_kabupaten' => 'required|integer|exists:kabupatens,id',
                'alamat' => 'required|string'
            ];
        } else {
            return [
                'nama_desa' => 'required|string',
                'nama_kades' => 'required|string',
                'id_kecamatan' => 'required|integer|exists:kecamatans,id',
                'id_kabupaten' => 'required|integer|exists:kabupatens,id',
                'alamat' => 'required|string'
            ];
        }
    }
}
