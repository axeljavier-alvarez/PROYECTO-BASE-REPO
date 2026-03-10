<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserInformation extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'cui',
        'telefono',
        'fecha_nacimiento',
        'correo',
        'sexo',
        'user_id',
    ];

    protected $appends = [
        'nombre_completo',
        'nombre_corto',
        'url_photo',
    ];


    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function domicilio() : HasOne {
        return $this->hasOne(Domicilio::class);
    }

    public function getNombreCompletoAttribute() {
        return $this->nombres.' '.$this->apellidos;
    }

    public function getUrlPhotoAttribute() {
        return $this->foto ? Storage::url($this->foto) : null;
    }

    public function getNombreCortoAttribute() {

        $prepositions = ['de', 'del', 'la', 'los', 'las'];

        $nombres = explode(" ", $this->nombres)[0];
        $apellidos = str_replace($prepositions,"", $this->apellidos);
        $apellidos = explode(" ",$apellidos)[0];

        return $nombres.' '.$apellidos;
    }

    public function scopeWhereNombreCompleto(Builder $query, $nombre_completo) {
        $concat = "TRIM(CONCAT(
            user_information.nombres, ' ',
            user_information.apellidos
        ))";

        return $query->where(DB::raw($concat), 'like', "%{$nombre_completo}%");
    }
}
