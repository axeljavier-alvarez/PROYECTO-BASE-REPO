<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

// Route::view('/','welcome')->name('home');
Route::redirect('/','dashboard')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';


Route::get('/captcha-muni', function () {

    $text = Str::random(6);
    session(['captcha_text' => $text]);
    return captchaGenerate($text);

})->name('captcha');






