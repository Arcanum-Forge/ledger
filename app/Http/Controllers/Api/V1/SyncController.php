<?php

// app/Http/Controllers/Api/V1/SyncController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class SyncController extends Controller
{
    abstract protected function query(): Builder;
    abstract protected function resource(): string;

    /** Hook for per-endpoint filters. */
    protected function filters(Request $request, Builder $query): Builder
    {
        return $query;
    }

    public function index(Request $request)
    {
        $v = $request->validate([
            'updated_since' => ['nullable', 'date'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $query = $this->filters($request, $this->query())
            ->when($v['updated_since'] ?? null, fn ($q, $d) => $q->where('updated_at', '>=', $d))
            ->orderBy('updated_at')
            ->orderBy('id');

        $resource = $this->resource();

        return $resource::collection($query->cursorPaginate($v['per_page'] ?? 100));
    }

    public function show(int $id)
    {
        $resource = $this->resource();

        return new $resource($this->query()->findOrFail($id));
    }
}