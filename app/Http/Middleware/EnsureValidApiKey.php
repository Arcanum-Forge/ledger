<?php

// app/Http/Middleware/EnsureValidApiKey.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureValidApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json'); // JSON errors always

        $provided = $request->bearerToken() ?? $request->header('X-API-Key');
        $hashes = config('services.ledger_api.key_hashes', []);

        if ($provided && $hashes) {
            $hash = hash('sha256', $provided);
            foreach ($hashes as $valid) {
                if (hash_equals($valid, $hash)) {
                    return $next($request);
                }
            }
        }

        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}