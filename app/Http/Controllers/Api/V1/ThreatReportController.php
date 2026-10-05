<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Resources\Api\V1\ThreatReportResource;
use App\Models\ThreatReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ThreatReportController extends SyncController
{
    protected function query(): Builder { return ThreatReport::query()->with(['monsters:id,name', 'region:id,name', 'kingdom:id,name']); }
    protected function resource(): string { return ThreatReportResource::class; }

    protected function filters(Request $request, Builder $query): Builder
    {
        return $query->when($request->query('status'), fn ($q, $s) => $q->where('status', $s));
    }
}