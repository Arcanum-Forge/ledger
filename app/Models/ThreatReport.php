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
    'report_number',
    'title',
    'region_id',
    'kingdom_id',
    'type',
    'level',
    'status',
    'sightings',
    'description',
])]
class ThreatReport extends Model
{
    use HasFactory, HasUniqueSlug;

    protected function casts(): array
    {
        return [
            'sightings' => 'integer',
            'level_severity' => 'integer',
        ];
    }

    /** Severity order for the 'level' string — least severe first. */
    public const LEVELS = ['Elevated', 'Severe', 'Critical', 'Moderate'];

    protected static function booted(): void
    {
        // level is a free-form label; level_severity is what everything
        // actually sorts and orders by, since alphabetical order
        // (Critical, Elevated, Severe) doesn't match real severity
        // (Elevated < Severe < Critical).
        static::saving(function (ThreatReport $report) {
            $index = array_search($report->level, self::LEVELS, true);
            $report->level_severity = $index === false ? 0 : $index + 1;
        });
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function kingdom(): BelongsTo
    {
        return $this->belongsTo(Kingdom::class);
    }

    protected function levelColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->level) {
                'Critical' => 'text-threat-critical',
                'Severe' => 'text-threat-high',
                default => 'text-threat-mid',
            },
        );
    }
}
