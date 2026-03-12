<?php

namespace App\Models\DesarrolloSocial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TramiteConstancia extends Model
{
     use HasFactory;

    protected $fillable = ['nombre', 'slug', 'path'];

    public $timestamps = false;


    public function requisitos()
    {
        return $this->belongsToMany(
            RequisitoConstancia::class,
            'requisito_tramite_constancias',
            'tramite_constancia_id',
            'requisito_constancia_id'
    );
    }
}
