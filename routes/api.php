<?php

// routes/api.php
use App\Http\Controllers\Api\V1\{FactionController, KingdomController, MonsterController, ThreatReportController};
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'throttle:ledger-api'])->group(function () {
    Route::get('monsters', [MonsterController::class, 'index']);
    Route::get('monsters/{id}', [MonsterController::class, 'show']);

    Route::get('threat-reports', [ThreatReportController::class, 'index']);
    Route::get('threat-reports/{id}', [ThreatReportController::class, 'show']);

    Route::get('kingdoms', [KingdomController::class, 'index']);
    Route::get('kingdoms/{id}', [KingdomController::class, 'show']);

    Route::get('factions', [FactionController::class, 'index']);
    Route::get('factions/{id}', [FactionController::class, 'show']);
});