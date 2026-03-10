<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Documento extends Model
{
    protected $fillable = [
        'nombre',
        'path',
        'solicitud_id',
    ];

    protected $appends = ['url'];

    public function documentos() {
        return $this->belongsTo(Solicitud::class);
    }

    public function getUrlAttribute() {
        return Storage::url($this->path);
    }

}
