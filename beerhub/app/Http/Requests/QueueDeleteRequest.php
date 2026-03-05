<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueueDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'queue_ids' => ['required', 'array', 'min:1'],
            'queue_ids.*' => ['string'],
        ];
    }
}
