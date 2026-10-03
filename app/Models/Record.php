<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Record extends Model
{
    use HasFactory;
    use HasUniqueSlug;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'content',
        'category',
        'era',
        'date',
        'author_id',
        'importance',
        'confidential',
    ];

    protected $casts = [
        'confidential' => 'boolean',
        'importance_level' => 'integer',
    ];

    /** Significance order for the 'importance' string — least significant first. */
    public const IMPORTANCE_LEVELS = ['Notable', 'Important', 'Critical'];

    protected static function booted(): void
    {
        // importance is a free-form label; importance_level is what
        // everything actually sorts and orders by, since alphabetical order
        // (Critical, Important, Notable) doesn't match real significance
        // (Notable < Important < Critical).
        static::saving(function (Record $record) {
            $index = array_search($record->importance, self::IMPORTANCE_LEVELS, true);
            $record->importance_level = $index === false ? 0 : $index + 1;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}