<?php

namespace App\Http\Requests\Question;

use App\Rules\{OnlyAsDraft, WithQuestionMark};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property mixed $question
 */
class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $question = $this->route()->question;

        return Gate::allows('update', $question);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @phpstan-ignore-next-line */
        $question = $this->route()->question;

        return [
            'question' => [
                'required',
                new OnlyAsDraft($question),
                new WithQuestionMark(),
                'min:10',
                'unique:questions,' . $question->id,
                // Rule::unique('questions')->ignore($this->route()->question->id),
            ],
        ];
    }
}
