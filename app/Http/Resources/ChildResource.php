<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChildResource extends JsonResource
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
            'age' => $this->age,
            'grade_id' => $this->grade_id,
            'grade' => $this->grade ? $this->grade->name : null,
            'user_id' => $this->user_id,
            'current_stage_id' => $this->current_stage_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 