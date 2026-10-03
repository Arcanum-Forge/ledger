<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faction extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'slug',
        'name',
        'title',
        'description',
        'type',
        'leader_id',
        'kingdom_id',
        'members',
        'alignment',
        'influence',
        'status',
        'status_description',
    ];

    protected $casts = [
        'influence' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function kingdom(): BelongsTo
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Leader::class);
    }
}