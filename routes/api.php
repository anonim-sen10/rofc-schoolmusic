<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AiAgentController;

Route::middleware('ai.agent')->group(function () {
    Route::get('/ai/schema', [AiAgentController::class, 'getSchema']);
    Route::post('/ai/query', [AiAgentController::class, 'runQuery']);
});
