<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransaksi_PajakRequest extends FormRequest
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
                'id_transaksi' => 'required|integer|exists:transaksis,id_transaksi',
                'id_pajak' => 'nullable|array|exists:pajaks,id',
                'nominal' => 'nullable|array'
            ];
        } else {
            return [
                'id_transaksi' => 'required|integer|exists:transaksis,id_transaksi',
                'id_pajak' => 'nullable|array|exists:pajaks,id',
                'nominal' => 'nullable|array'
            ];
        }
    }
}
