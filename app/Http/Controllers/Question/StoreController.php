<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Resources\QuestionResource;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request)
    {
        $question = user()
            ->questions()
            ->create([
                'question' => $request->question,
                'status'   => 'draft',
            ]);

        return QuestionResource::make($question);
    }
}
