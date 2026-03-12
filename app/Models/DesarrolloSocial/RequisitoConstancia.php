<?php

namespace App\Models\DesarrolloSocial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitoConstancia extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['nombre', 'slug'];

    public function tramites()
    {
        return $this->belongsToMany(
            TramiteConstancia::class,
            'requisito_tramite_constancias',
            'requisito_constancia_id',
            'tramite_constancia_id'
        );
    }

}
