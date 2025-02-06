<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class UpdateController extends Controller
{
    public function __invoke(Question $question)
    {
        $question->update([
            'question' => request()->question,
        ]);

        return QuestionResource::make($question);
    }
}
