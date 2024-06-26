<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'category' => $this->category->name,
            'subcategory' => $this->subcategory->name,
            'department' => $this->department->name,
            'created_at' => $this->created_at->locale('es')->diffForHumans(),
            'updated_at' => $this->updated_at->locale('es')->diffForHumans(),
            
            
        ];
    }
}
