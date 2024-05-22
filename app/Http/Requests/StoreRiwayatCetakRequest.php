<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiwayatCetakRequest extends FormRequest
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
                'id_transaksi' => 'nullable|integer|exists:transaksis,id_transaksi',
                'jenis_pajak' => 'nullable|string',
                'nama_pencetak' => 'nullable|string'
            ];
        } else {
            return [
                'id_transaksi' => 'required|integer|exists:transaksis,id_transaksi',
                'jenis_pajak' => 'nullable|string',
                'nama_pencetak' => 'nullable|string'
            ];
        }
    }
}
