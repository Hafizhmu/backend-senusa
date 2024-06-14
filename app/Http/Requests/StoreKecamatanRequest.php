<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKecamatanRequest extends FormRequest
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
        if ($this->has('kecamatan')) {
            $this->merge([
                'kecamatan' => 'Kecamatan ' . $this->kecamatan,
            ]);
        }
        if (request()->isMethod('post')) {
            return [
                'id_kabupaten' => 'required|integer|exists:kabupatens,id',
                'kecamatan' => [
                    'required',
                    'string',
                    'unique:kecamatans,kecamatan,NULL,id,id_kabupaten,' . $this->id_kabupaten,
                ]
            ];
        } else {
            return [
                'id_kabupaten' => 'required|integer|exists:kabupatens,id',
                'kecamatan' => [
                    'required',
                    'string',
                    'unique:kecamatans,kecamatan,NULL,id,id_kabupaten,' . $this->id_kabupaten,
                ]
            ];
        }
    }
}
