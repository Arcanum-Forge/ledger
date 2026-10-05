<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\MonsterResource;
use App\Models\Monster;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\V1\SyncController;

class MonsterController extends SyncController
{
    protected function query(): Builder { return Monster::query(); }
    protected function resource(): string { return MonsterResource::class; }
}