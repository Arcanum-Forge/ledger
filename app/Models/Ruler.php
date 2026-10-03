<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[RouteKey('slug')]
#[Fillable([
    'slug',
    'honorific',
    'name',
    'bio',
    'notes'
])]
class Ruler extends Model
{
    use HasFactory, HasUniqueSlug;

    public function kingdoms(): HasMany
    {
        return $this->hasMany(Kingdom::class);
    }

    protected function fullTitle(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->honorific} {$this->name}",
        );
    }

}