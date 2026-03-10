<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use App\Models\Traits\Searchable;
use App\Models\Zona;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solicitud extends Model
{

    public const TIPO_PERSONA = [
        'Individual',
        'Juridica'
    ];

    use Searchable;

    protected $table="solicitudes";
    protected $appends = ['nombre_completo'];
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'cui',
        'nit',
        'patente_comercio',
        'telefono',
        'correo',
        'zona_id',
        'colonia',
        'domicilio',
        'actividad_negocio',
        'largo',
        'ancho',
        'observaciones',
        'sede_id',
        'tipo_persona',
    ];

    public function expediente() {
        return $this->hasOne(Expediente::class);
    }

    public function documentos() {
        return $this->hasMany(Documento::class);
    }

    public function sede() {
        return $this->belongsTo(Sede::class);
    }

    public function zona() {
        return $this->belongsTo(Zona::class);
    }

    public function getNombreCompletoAttribute() {
        $nombres = [
            $this->primer_nombre,
            $this->segundo_nombre,
            $this->primer_apellido,
            $this->segundo_apellido,
        ];

        $nombres = array_filter($nombres, function ($nombre) {
            return !is_null($nombre) && $nombre !== '';
        });
        
        return implode(' ', $nombres);
    } 
    
    public function scopeWhereNombreCompleto(Builder $query, $nombre_completo) {
        $concat = "TRIM(CONCAT(
            solicitudes.primer_nombre, ' ',
            IFNULL(solicitudes.segundo_nombre, ''),
            ' ',
            solicitudes.primer_apellido, ' ',
            IFNULL(solicitudes.segundo_apellido, '')
        ))";

        return $query->where(DB::raw($concat), 'like', "%{$nombre_completo}%");
    }
}
