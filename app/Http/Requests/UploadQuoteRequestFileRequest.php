<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UploadQuoteRequestFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                File::types([
                    'jpg',
                    'jpeg',
                    'png',
                    'webp',
                    'pdf',
                ])->max('15mb'),
            ],
            'categoryCode' => [
                'nullable',
                'string',
                'max:80',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Selecciona un archivo para continuar.',
            'categoryCode.max' => 'La categoría del archivo no es válida.',
        ];
    }
}
