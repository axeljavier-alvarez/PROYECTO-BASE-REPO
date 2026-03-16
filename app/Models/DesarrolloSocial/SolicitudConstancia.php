<?php

namespace App\Models\DesarrolloSocial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudConstancia extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_constancias';
    protected $fillable = [
        'no_solicitud',
        'anio',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'cui',
        'domicilio',
        'observaciones',
        'zona_constancia_id',
        'estado_constancia_id',
        'razon',
        'tramite_constancia_id'
    ];

    // relacion de uno a muchos con zonas
    public function zonaConstancia()
    {
        return $this->belongsTo(ZonaConstancia::class, 'zona_constancia_id');
    }

    // relacion de uno a muchos con estado
    public function estadoConstancia()
    {
        return $this->belongsTo(EstadoConstancia::class, 'estado_constancia_id');
    }

    protected function builder()
    {
        return SolicitudConstancia::query()->with('estadoConstancia');
    }

    // relacion muchos a muchos con requisitostramites
    public function requisitosTramitesConstancias()
    {
        return $this->belongsToMany(
            RequisitoTramiteConstancia::class,
            'solicitudes_has_requisitos_tramites',
            'solicitud_constancia_id', 
            'requisito_tramite_constancia_id'
        );
    }

    // relacion con detalle solicitud
    public function detallesSolicitudes()
    {
        return $this->hasMany(
            DetalleSolicitud::class, 'solicitud_constancia_id'
        );
    }

   public function bitacorasConstancias() {
        return $this->hasMany(BitacoraConstancia::class, 'solicitud_constancia_id');
    }

   public function tramiteConstancia()
   {
    return $this->belongsTo(TramiteConstancia::class);
   }
   



   
}
