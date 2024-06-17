<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "faltas" => $this->faltas->count(),
            "curso" => $this->curso,
            "periodo" => $this->periodo,
            "documento" => $this->documento ? url('/') . Storage::url($this->documento) : null,
            "faltasEvento" => $this->faltas,
            "ra" => $this->ra,
            "is_admin" => $this->is_admin,
            'image' => url('/') . $this->avatar,
            "is_blocked" => $this->is_blocked
        ];
    }
}
