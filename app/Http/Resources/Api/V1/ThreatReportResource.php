<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreatReportResource extends JsonResource
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
            'report_number'  => $this->report_number,
            'title'          => $this->title,
            'type'           => $this->type,
            'level'          => $this->level,
            'level_severity' => $this->level_severity,
            'status'         => $this->status,
            'sightings'      => $this->sightings,
            'description'    => $this->description,
            'monsters' => $this->whenLoaded('monsters', fn () =>
                $this->monsters->map(fn ($m) => ['id' => $m->id, 'name' => $m->name])->all()
            ),
            'region_id'      => $this->region_id,
            'region'         => $this->whenLoaded('region', fn () => $this->region?->name),
            'kingdom_id'     => $this->kingdom_id,
            'kingdom'        => $this->whenLoaded('kingdom', fn () => $this->kingdom?->name),
            'updated_at'     => $this->updated_at?->toIso8601String(),
        ];
    }
}
