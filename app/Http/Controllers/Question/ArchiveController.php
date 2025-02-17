<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Support\Facades\Gate;

class ArchiveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Question $question)
    {
        Gate::authorize('archive', $question);

        $question->delete();

        return response()->noContent();
    }
}
