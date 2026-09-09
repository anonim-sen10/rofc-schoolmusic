<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AiAgentController;

Route::middleware('ai.agent')->group(function () {
    Route::get('/ai/schema', [AiAgentController::class, 'getSchema']);
    Route::post('/ai/query', [AiAgentController::class, 'runQuery']);
    Route::post('/ai/action', [AiAgentController::class, 'executeAction']);
    Route::get('/ai/files', [AiAgentController::class, 'listFiles']);
    Route::get('/ai/file', [AiAgentController::class, 'readFile']);
    Route::post('/ai/file', [AiAgentController::class, 'writeFile']);
    Route::get('/ai/search', [AiAgentController::class, 'searchCode']);
});
