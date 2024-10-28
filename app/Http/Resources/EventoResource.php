<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventoResource extends JsonResource
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
          "date" => Carbon::parse($this->date)->format('d/m/Y'),
          "time" => Carbon::parse($this->time)->format('H:i'),
          "service" => $this->service
        ];
    }
}
