<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Ristretto' => ['required', 'integer', 'min:0'],
            'Espresso' => ['required', 'integer', 'min:0'],
            'Lungo' => ['required', 'integer', 'min:0'],
        ];
    }
}
