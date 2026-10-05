<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[RouteKey('slug')]
#[Fillable([
    'slug',
    'name',
    'title',
    'description',
    'region_id',
    'population',
    'alignment',
    'threat',
    'founded',
    'region_id',
    'ruler_id',
])]
class Kingdom extends Model
{
    use HasFactory, HasUniqueSlug;

    protected function casts(): array
    {
        return [
            'threat' => 'integer',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function ruler(): BelongsTo
    {
        return $this->belongsTo(Ruler::class);
    }

    protected function threatColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->threat >= 70 => 'text-threat-critical',
                $this->threat >= 40 => 'text-threat-high',
                default => 'text-threat-low',
            },
        );
    }
}
