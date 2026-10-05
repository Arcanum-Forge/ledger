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
    'title',
    'excerpt',
    'content',
    'category',
    'era',
    'date',
    'author_id',
    'importance',
    'confidential',
    'importance_level',
])]
class Record extends Model
{
    use HasFactory, HasUniqueSlug;

    protected function casts(): array
    {
        return [
            'confidential' => 'boolean',
            'importance_level' => 'integer',
        ];
    }

    /** Significance order for the 'importance' string — least significant first. */
    public const IMPORTANCE_LEVELS = [
        'Notable' => 1,
        'Important' => 2,
        'Critical' => 3,
    ];

    protected static function booted(): void
    {
        static::saving(function (Record $record) {
            $record->importance_level =
                self::IMPORTANCE_LEVELS[$record->importance] ?? 0;
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
