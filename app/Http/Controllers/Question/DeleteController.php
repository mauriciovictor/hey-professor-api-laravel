<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Question $question)
    {

        Gate::authorize('forceDelete', $question);

        $question->forceDelete();

        return response()->noContent();
    }
}
