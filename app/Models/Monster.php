<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monster extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'slug',
        'name',
        'classification',
        'habitat',
        'kingdom_id',
        'threat',
        'sightings',
        'status',
        'description',
    ];

    protected $casts = [
        'sightings' => 'integer',
        'threat_level' => 'integer',
    ];

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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function kingdom(): BelongsTo
    {
        return $this->belongsTo(Kingdom::class);
    }
}