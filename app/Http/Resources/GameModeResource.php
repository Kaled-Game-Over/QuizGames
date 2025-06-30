<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameModeResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'game_config' => $this->game_config,
            'is_active' => $this->is_active,
            'contents' => GameModeContentResource::collection($this->whenLoaded('contents')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 