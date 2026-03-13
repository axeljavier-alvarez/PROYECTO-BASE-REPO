<?php

namespace App\Models\DesarrolloSocial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EstadoConstancia extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];
    public $timestamps = false;

    
}
