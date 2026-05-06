<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LikePlatform\AI\Http\Controllers\AIPlaygroundController;
use LikePlatform\AI\Http\Controllers\PromptTemplateController;
use LikePlatform\AI\Http\Controllers\AIStatsController;

Route::middleware(['web', 'auth', 'verified'])->prefix('ai')->name('ai.')->group(function (): void {
    Route::get('/playground', [AIPlaygroundController::class, 'index'])->name('playground');
    Route::get('/stats', [AIStatsController::class, 'index'])->name('stats');
    Route::resource('templates', PromptTemplateController::class)->except(['show']);
});
