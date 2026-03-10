<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use Searchable;

    protected $fillable = [
        'largo',
        'ancho',
        'descripcion',
        'coordenadas',
        'area_sede_id',
        'solicitud_id',
    ];

    public function area_sede() {
        return $this->belongsTo(AreaSede::class);
    }

    public function solicitud() {
        return $this->belongsTo(Solicitud::class);
    }

    public function workflows() {
        return $this->hasMany(Workflow::class);
    }

    public function latestWorkflow() {
        return $this->hasOne(Workflow::class)->latestOfMany('id');
    }
}
