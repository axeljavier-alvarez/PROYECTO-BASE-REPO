<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory, Searchable;

    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function expedientes() {
        return $this->hasMany(Expediente::class);
    }

    public function solicitudes() {
        return $this->hasMany(Solicitud::class);
    }

    public function areas() {
        return $this->hasMany(AreaSede::class);
    }
}
