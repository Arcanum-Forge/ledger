<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruler extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = ['slug', 'honorific', 'name', 'bio', 'notes'];

    public function kingdoms(): HasMany
    {
        return $this->hasMany(Kingdom::class);
    }

    public function getFullTitleAttribute(): string
    {
        return "{$this->honorific} {$this->name}";
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}