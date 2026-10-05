<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonsterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'slug'           => $this->slug,
            'name'           => $this->name,
            'classification' => $this->classification,
            'habitat'        => $this->habitat,
            'kingdom_id'     => $this->kingdom_id,
            'threat'         => $this->threat,
            'threat_level'   => $this->threat_level,
            'sightings'      => $this->sightings,
            'status'         => $this->status,
            'description'    => $this->description,
            'updated_at'     => $this->updated_at?->toIso8601String(),
            'monsters' => $this->whenLoaded('monsters', fn () =>
                $this->monsters->map(fn ($m) => ['id' => $m->id, 'name' => $m->name])->all()
            ),
        ];
    }
}
