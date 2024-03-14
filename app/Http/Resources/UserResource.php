<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'department' => $this->department->name, // incluye el nombre del departamento al construir el recurso. El modelo User tiene una relación con el modelo Department.
           'roles' => $this->getRoleNames(),
           'permissions' => $this->getPermissionNames(),
        ];
    }
}
