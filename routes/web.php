<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', function () {
    $users = User::all();
    return $users;
});
Route::get('/cache', function () {
    Cache::put('key', 'value', 10);
    return Cache::get('key');
});

