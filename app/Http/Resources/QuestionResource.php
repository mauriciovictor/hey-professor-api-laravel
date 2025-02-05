<?php

namespace App\Http\Resources;

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
        return [
            'id'         => $this->resource->id,
            'question'   => $this->resource->question,
            'status'     => $this->resource->status,
            'user_id'    => $this->resource->user_id,
            'created_by' => [
                'id'   => $this->resource->user->id,
                'name' => $this->resource->user->name,
            ],
            'created_at' => $this->resource->created_at->format('Y-m-d'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d'),
        ];
    }
}
