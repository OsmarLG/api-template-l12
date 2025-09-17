<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'test'
    ]);
});

Route::get('/test2', function () {
    return response()->json([
        'message' => 'quitando el check'
    ]);
});

