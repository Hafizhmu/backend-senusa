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
                'id_projek' => 'integer|exists:projeks,id_projek',
                'id_desa' => 'integer|exists:desas,id_desa',
                'harga' => 'nullable|integer',
                'status_kontrak' => 'nullable|integer',
                'status_pembayaran' => 'nullable|integer',
                'tanggal_pembayaran' => 'nullable|string',
                'tanggal_transaksi' => 'nullable|string',
                'id_perusahaan' => 'nullable|integer',
                'bukti' => 'nullable|file|mimes:png,jpg,jpeg'
            ];
        } else {
            return [
                'id_projek' => 'nullable|integer|exists:projeks,id_projek',
                'id_desa' => 'nullable|integer|exists:desas,id_desa',
                'harga' => 'nullable|integer',
                'status_kontrak' => 'nullable|integer',
                'status_pembayaran' => 'nullable|integer',
                'tanggal_pembayaran' => 'nullable|string',
                'id_perusahaan' => 'nullable|integer',
                'tanggal_transaksi' => 'nullable|string',
                'bukti' => 'nullable|file|mimes:png,jpg,jpeg'
            ];
        }
    }
}
