<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
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
                'id_projek' => 'required|integer|exists:projeks,id_projek',
                'id_desa' => [
                    'required', function ($attribute, $value, $validator) {
                        // Check if value is an integer
                        if (is_numeric($value) && intval($value) == $value) {
                            return true; // Valid integer
                        }

                        // Check if value is an array
                        if (is_array($value)) {
                            // Validate each element in the array as an integer
                            foreach ($value as $element) {
                                if (!is_numeric($element) || intval($element) != $element) {
                                    return false; // Invalid element
                                }
                            }
                            return true; // Valid array of integers
                        }

                        return false; // Invalid value type
                    },
                ],
                'harga' => 'required|integer',
                'status_kontrak' => 'required|integer',
                'status_pembayaran' => 'required|integer',
                'tanggal_pembayaran' => 'nullable|string',
                'tanggal_transaksi' => 'required|string',
                'id_perusahaan' => 'required|integer|exists:perusahaans,id',
                'bukti' => 'file|mimes:png,jpg,jpeg'
            ];
        } else {
            return [
                'id_projek' => 'required|integer|exists:projeks,id_projek',
                'id_desa' => 'required|integer|exists:desas,id_desa',
                'harga' => 'required|integer',
                'status_kontrak' => 'required|integer',
                'status_pembayaran' => 'required|integer',
                'tanggal_pembayaran' => 'nullable|string',
                'tanggal_transaksi' => 'required|string',
                'id_perusahaan' => 'required|integer|exists:perusahaans,id',
                'bukti' => 'file|mimes:png,jpg,jpeg'
            ];
        }
    }
}
