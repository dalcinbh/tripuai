<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelRequestResource extends JsonResource
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
            'requester_name' => $this->requester_name,
            'destination' => $this->destination,
            'status' => $this->status,
            'departure_date' => $this->departure_date->format('d/m/Y'),
            'return_date' => $this->return_date->format('d/m/Y'),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
