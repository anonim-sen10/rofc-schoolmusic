<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AiAgentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('AI_AGENT_API_KEY');
        if (empty($expectedKey)) {
            $expectedKey = 'PasswordRahasiaBotSaya123';
        }

        // Check X-AI-API-KEY header, X-API-KEY header, Bearer token, or query parameter
        $providedKey = $request->header('X-AI-API-KEY')
            ?? $request->header('X-API-KEY')
            ?? $request->bearerToken()
            ?? $request->input('api_key');

        if (empty($providedKey) || (string)$providedKey !== (string)$expectedKey) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized. API Key tidak valid atau belum diisi.'
            ], 401);
        }

        return $next($request);
    }
}
