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
    'threat_level',
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
    public const THREAT_LEVELS = [
        'Low' => 1,
        'Moderate' => 2,
        'High' => 3,
        'Extreme' => 4,
    ];

    protected static function booted(): void
    {
        static::saving(function (Monster $monster) {
            $monster->threat_level = self::THREAT_LEVELS[$monster->threat] ?? 0;
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
