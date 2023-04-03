<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AttemptStoreRequest extends FormRequest
{
    /**
     * This user is logged in and they are submitting an attempt for themselves
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'question_id' => ['required', 'exists:questions,id'],
            'choice' => ['required', 'integer', 'between:0,9']
        ];
    }
}
