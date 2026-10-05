<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[RouteKey('slug')]
#[Fillable([
    'slug',
    'name',
    'bio',
    'notes',
])]
class Leader extends Model
{
    use HasFactory, HasUniqueSlug;

    public function factions(): HasMany
    {
        return $this->hasMany(Faction::class);
    }
}
