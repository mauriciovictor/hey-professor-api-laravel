<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Models\Question;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request)
    {
        $question = Question::create([
            'question' => $request->question,
            'status'   => 'draft',
            'user_id'  => auth()->user()->id,
        ]);

        // dd(['data' => $question->toArray()]);

        return response()->json(
            ['data' => $question->toArray()],
            Response::HTTP_CREATED
        );
    }
}
