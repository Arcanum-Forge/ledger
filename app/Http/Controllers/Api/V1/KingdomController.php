<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\KingdomResource;
use App\Models\Kingdom;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\V1\SyncController;

class KingdomController extends SyncController
{
    protected function query(): Builder { return Kingdom::query(); }
    protected function resource(): string { return KingdomResource::class; }
}
