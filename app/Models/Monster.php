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
    'classification',
    'habitat',
    'kingdom_id',
    'threat',
    'sightings',
    'status',
    'description',
])]
class Monster extends Model
{
    use HasFactory, HasUniqueSlug;

    protected function casts(): array
    {
        return [
            'sightings' => 'integer',
            'threat_level' => 'integer',
        ];
    }

    /** Severity order for the 'threat' string — worst first. */
    public const THREAT_LEVELS = ['Low', 'Moderate', 'High', 'Extreme'];

    protected static function booted(): void
    {
        // threat is a free-form label; threat_level is what everything
        // actually sorts and orders by, since 'Extreme' < 'High'
        // alphabetically would sort backwards from real severity.
        static::saving(function (Monster $monster) {
            $index = array_search($monster->threat, self::THREAT_LEVELS, true);
            $monster->threat_level = $index === false ? 0 : $index + 1;
        });
    }

    public function kingdom(): BelongsTo
    {
        return $this->belongsTo(Kingdom::class);
    }

    protected function threatColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->threat) {
                'Extreme' => 'text-threat-critical',
                'High' => 'text-threat-high',
                'Moderate' => 'text-threat-mid',
                default => 'text-muted',
            },
        );
    }
}
