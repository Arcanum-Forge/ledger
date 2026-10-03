<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[RouteKey('slug')]
#[Fillable([
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
])]
class Faction extends Model
{
    use HasFactory, HasUniqueSlug;
    protected function casts(): array
    {
        return [
            'influence' => 'integer',
        ];
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