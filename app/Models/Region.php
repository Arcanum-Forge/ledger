<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = ['name', 'slug', 'description'];

    public function kingdoms(): HasMany
    {
        return $this->hasMany(Kingdom::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}