<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\DaysOfWeek;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $informations = DaysOfWeek::from($this->day->value);
        return [
            

            "id" => $this->id,
            'day' => [
              'name' => $informations->name,
              'value' => $informations->value
            ],
            'opening_time' => Carbon::parse($this->opening_time)->format('H:i'),
            'closing_time' => Carbon::parse($this->closing_time)->format('H:i'),
        ];
    }
}
