<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'slug',
        'name',
        'bio',
        'notes',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }
}