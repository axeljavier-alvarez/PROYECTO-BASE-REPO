<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AreaSede extends Model
{
    protected $table = 'areas_sede';
    protected $appends = ['url_imagen'];
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'path_imagen',
        'sede_id',
    ];

    public function sede(){
        return $this->belongsTo(Sede::class);
    }

    public function getUrlImagenAttribute(){
        return $this->path_imagen ? Storage::url($this->path_imagen) : null;
    }
}
