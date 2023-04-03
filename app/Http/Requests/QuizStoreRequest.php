<?php

namespace App\Http\Requests;

use App\Models\Quiz;
use App\Policies\QuizPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class QuizStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) Auth::user()?->can('create', Quiz::findOrFail($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:75'],
            'description' => ['string', 'max:255'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['string', 'required'],
            'questions.*.choices' => ['array', 'min:2'],
            'questions.*.answer' => ['required', 'numeric']
        ];
    }
}
