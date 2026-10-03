<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Gives a Livewire component one or more "searchable relation pickers":
 * a text input that live-searches a model by name, and, once a result is
 * picked, stores its id + a display label on the component.
 *
 * The Kingdom manager needs four of these (filter by ruler, filter by
 * region, pick a ruler on the form, pick a region on the form) that were
 * previously four near-identical copies of the same ~20 lines. Declare
 * each one in searchableRelations() instead:
 *
 *     protected function searchableRelations(): array
 *     {
 *         return [
 *             'ruler-form' => [
 *                 'model'          => Ruler::class,
 *                 'idProperty'     => 'rulerId',
 *                 'nameProperty'   => 'rulerName',
 *                 'searchProperty' => 'rulerFormSearch',
 *                 'label'          => fn (Ruler $r) => $r->full_title,
 *             ],
 *         ];
 *     }
 *
 * Then, from Blade: wire:model.live="rulerFormSearch",
 * $this->relationResults('ruler-form') to list matches,
 * wire:click="selectRelation('ruler-form', {{ $r->id }})" to pick one,
 * wire:click="clearRelation('ruler-form')" to clear it.
 *
 * Any component using this trait must implement searchableRelations().
 */
trait HasSearchableRelations
{
    /**
     * @return array<string, array{
     *     model: class-string<Model>,
     *     idProperty: string,
     *     nameProperty: string,
     *     searchProperty: string,
     *     searchColumn?: string,
     *     label: callable(Model): string,
     *     resetsCursor?: bool,
     * }>
     */
    abstract protected function searchableRelations(): array;

    /**
     * Resolve and normalize a picker's config, filling in defaults.
     */
    protected function relationConfig(string $key): array
    {
        $config = $this->searchableRelations()[$key]
            ?? throw new InvalidArgumentException("Unknown searchable relation [{$key}].");

        return $config + [
            'searchColumn' => 'name',
            'resetsCursor' => false,
        ];
    }

    /**
     * The top matches for a picker's current search term. Wrap this in a
     * #[Computed] method on the component if you want it cached per request
     * (recommended — that's what the original per-field computed methods did).
     */
    public function relationResults(string $key): Collection
    {
        $config = $this->relationConfig($key);
        $term = $this->{$config['searchProperty']};

        return $config['model']::query()
            ->when($term, fn(Builder $q) => $q->where($config['searchColumn'], 'like', "%{$term}%"))
            ->orderBy($config['searchColumn'])
            ->limit(8)
            ->get();
    }

    /**
     * Pick a record: store its id, its display label, and clear the search box.
     */
    public function selectRelation(string $key, string $id): void
    {
        $config = $this->relationConfig($key);
        $record = $config['model']::find($id);

        $this->{$config['idProperty']} = $id;
        $this->{$config['nameProperty']} = $record ? ($config['label'])($record) : '';
        $this->{$config['searchProperty']} = '';

        if ($config['resetsCursor']) {
            $this->cursor = null;
        }
    }

    /**
     * Clear a picker back to its empty state.
     */
    public function clearRelation(string $key): void
    {
        $config = $this->relationConfig($key);

        $this->{$config['idProperty']} = '';
        $this->{$config['nameProperty']} = '';
        $this->{$config['searchProperty']} = '';

        if ($config['resetsCursor']) {
            $this->cursor = null;
        }
    }

    /**
     * Fill in a picker's display name from whatever id is already set on
     * the component — e.g. hydrating a URL-bound filter's name on mount().
     */
    protected function hydrateRelationName(string $key): void
    {
        $config = $this->relationConfig($key);
        $id = $this->{$config['idProperty']};

        if ($id !== '' && $id !== null) {
            $record = $config['model']::find($id);
            $this->{$config['nameProperty']} = $record ? ($config['label'])($record) : '';
        }
    }
}