<?php

use App\Livewire\UnidadConvivenciaSocial\PasosPedales\AsignacionSolicitud;
use App\Livewire\UnidadConvivenciaSocial\PasosPedales\AutorizacionSolicitud;
use App\Livewire\UnidadConvivenciaSocial\PasosPedales\RecepcionSolicitud;
use App\Livewire\UnidadConvivenciaSocial\PasosPedales\Sedes;
use App\Livewire\UnidadConvivenciaSocial\PasosPedales\Solicitud;
use App\Livewire\UnidadConvivenciaSocial\PasosPedales\TimeLineSolicitud;

use Illuminate\Support\Facades\Route;

Route::prefix('pasos-pedales')->group(function () {

    Route::get('solicitud',Solicitud::class)
        ->middleware(['can:page.view.pasos-pedales.solicitud'])
        ->name('pasos-pedales.solicitud');

    Route::get('recepcion-solicitud',RecepcionSolicitud::class)
        ->middleware(['can:page.view.pasos-pedales.recepcion-solicitud'])
        ->name('pasos-pedales.recepcion-solicitud');

    Route::get('asignacion-solicitud',AsignacionSolicitud::class)
        ->middleware(['can:page.view.pasos-pedales.asignacion-solicitud'])
        ->name('pasos-pedales.asignacion-solicitud');

    Route::get('autorizacion-solicitud',AutorizacionSolicitud::class)
        ->middleware(['can:page.view.pasos-pedales.autorizacion-solicitud'])
        ->name('pasos-pedales.autorizacion-solicitud');

    Route::get('linea-tiempo-solicitud',TimeLineSolicitud::class)
        ->middleware(['can:page.view.pasos-pedales.linea-tiempo-solicitud'])
        ->name('pasos-pedales.linea-tiempo-solicitud');

    Route::get('sedes',Sedes::class)
        ->middleware(['can:page.view.pasos-pedales.sedes'])
        ->name('pasos-pedales.sedes');

});