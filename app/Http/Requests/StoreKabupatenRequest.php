<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreKabupatenRequest extends FormRequest
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
        if ($this->has('kabupaten')) {
            $this->merge([
                'kabupaten' => 'Kabupaten ' . $this->kabupaten,
            ]);
        }
        if (request()->isMethod('post')) {
            return [
                'kabupaten' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('kabupatens', 'kabupaten') // Gantilah 'your_table_name' dengan nama tabel yang sesuai
                ],
            ];
        } else {
            return [
                'kabupaten' => 'required|string'
            ];
        }
    }
}
