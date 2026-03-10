<?php


use Illuminate\Support\Facades\Route;

Route::prefix('constancias-residencias')->group(function () {

    Route::view('solicitud','desarrollo-social.constancias-residencias.solicitud')
        ->name('constancias-residencias.solicitud');

});