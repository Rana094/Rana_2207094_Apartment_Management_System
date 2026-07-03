<?php

use Illuminate\Support\Facades\Route;

// Public Front-Facing Routes
Route::get('/', function () {
    return view('landing');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

// Authentication View Routes
Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/waiting-approval', function () {
    return view('waiting-approval');
});
