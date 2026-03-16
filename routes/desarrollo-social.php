<?php


use Illuminate\Support\Facades\Route;

Route::prefix('constancias-residencias')->group(function () {

    Route::view('solicitud','desarrollo-social.constancias-residencias.solicitud')
        ->name('constancias-residencias.solicitud');


        Route::view('dashboard','desarrollo-social.constancias-residencias.solicitud')
        ->middleware(['can:page.view.constancias-residencias.dashboard'])
        ->name('constancias-residencias.dashboard');


});