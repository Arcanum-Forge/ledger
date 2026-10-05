<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\FactionResource;
use App\Models\Faction;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\V1\SyncController;

class FactionController extends SyncController
{
    protected function query(): Builder { return Faction::query(); }
    protected function resource(): string { return FactionResource::class; }
}