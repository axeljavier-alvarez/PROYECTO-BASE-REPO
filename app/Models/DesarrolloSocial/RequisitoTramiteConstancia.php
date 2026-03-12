<?php

namespace App\Models\DesarrolloSocial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitoTramiteConstancia extends Model
{
    use HasFactory;
    protected $table = 'requisito_tramite_constancias';
    protected $fillable = [
            'requisito_constancia_id',
            'tramite_constancia_id'
    ];
}
