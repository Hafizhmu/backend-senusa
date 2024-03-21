<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCvRequest extends FormRequest
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
                'nama_cv' => 'required|string',
                'nama_direktur' => 'required|string',
                'format_surat' => 'required|string'
            ];
        } else {
            return [
                'nama_cv' => 'required|string',
                'nama_direktur' => 'required|string',
                'format_surat' => 'required|string'
            ];
        }
    }
}
