<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AiAgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('AI_AGENT_API_KEY');

        // Jika API key belum di set di .env, kita blokir semua akses demi keamanan
        if (empty($expectedKey)) {
            return response()->json([
                'error' => 'API Key belum dikonfigurasi di server.'
            ], 500);
        }

        // Cek apakah header X-AI-API-KEY sesuai
        $providedKey = $request->header('X-AI-API-KEY');

        if ($providedKey !== $expectedKey) {
            return response()->json([
                'error' => 'Unauthorized. API Key tidak valid.'
            ], 401);
        }

        return $next($request);
    }
}
