<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YouTubeController;

// Homepage
Route::get('/', [YouTubeController::class, 'index'])->name('home');

// API Routes for YouTube
Route::get('/api/search', [YouTubeController::class, 'search'])->name('youtube.search');
Route::get('/api/trending', [YouTubeController::class, 'trending'])->name('youtube.trending');
Route::get('/api/video/{videoId}', [YouTubeController::class, 'videoDetails'])->name('youtube.video');
