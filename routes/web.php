<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'locale' => str_replace('_', '-', app()->getLocale()),
        'config' => config('app.name', 'Laravel'),
        'vorname' => 'Stefan',
    ]);
});

Route::get('/health', function () {
    return response()->json([
        'app' => 'Budget Frontend',
        'status' => 'ok',
    ]);
});
