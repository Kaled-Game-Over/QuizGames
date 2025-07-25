<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'contents' => LessonContentResource::collection($this->whenLoaded('contents')),
            'game_mode' => new GameModeResource($this->whenLoaded('gameMode')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 