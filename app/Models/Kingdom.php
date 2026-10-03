<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kingdom extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
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
        'ruler_id'
    ];

    protected $casts = ['threat' => 'integer'];


    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function ruler(): BelongsTo
    {
        return $this->belongsTo(Ruler::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getThreatColorAttribute(): string
    {
        return match (true) {
            $this->threat >= 70 => 'text-[#c14545]',
            $this->threat >= 40 => 'text-[#b98967]',
            default => 'text-[#7a9b6e]',
        };
    }

}