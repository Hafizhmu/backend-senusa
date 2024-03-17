<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransaksiRequest extends FormRequest
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
        if (request()->isMethod('put')) {
            return [
                'id_projek' => 'required|integer|exists:projeks,id_projek',
                'id_desa' => 'required|integer|exists:desas,id_desa',
                'id_kecamatan' => 'required|integer|exists:kecamatans,id',
                'id_kabupaten' => 'required|integer|exists:kabupatens,id',
                'status_kontrak' => 'required|boolean',
                'status_pembayaran' => 'required|boolean'
            ];
        } else {
            return [
                'id_projek' => 'required|integer|exists:projeks,id_projek',
                'id_desa' => 'required|integer|exists:desas,id_desa',
                'id_kecamatan' => 'required|integer|exists:kecamatans,id',
                'id_kabupaten' => 'required|integer|exists:kabupatens,id',
                'status_kontrak' => 'required|boolean',
                'status_pembayaran' => 'required|boolean'
            ];
        }
    }
}
