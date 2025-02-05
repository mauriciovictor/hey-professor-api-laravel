<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Question $question **/
        $question = $this->resource;

        return [
            'id'         => $question->id,
            'question'   => $question->question,
            'status'     => $question->status,
            'user_id'    => $question->user_id,
            'created_by' => [
                'id'   => $question->user->id,
                'name' => $question->user->name,
            ],
            'created_at' => $question->created_at->format('Y-m-d'),
            'updated_at' => $question->updated_at->format('Y-m-d'),
        ];
    }
}
