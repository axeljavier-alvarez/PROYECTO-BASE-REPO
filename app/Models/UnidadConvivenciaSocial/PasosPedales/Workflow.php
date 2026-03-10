<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
        'observacion',
        'user_id',
        'expediente_id',
        'estado_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function expediente() {
        return $this->belongsTo(Expediente::class);
    }

    public function estado() {
        return $this->belongsTo(Estado::class);
    }
}
