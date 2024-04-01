<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePajakRequest extends FormRequest
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
                'jenis_pajak' => 'required|string'
            ];
        } else {
            return [
                'jenis_pajak' => 'required|string'
            ];
        }
    }
}
