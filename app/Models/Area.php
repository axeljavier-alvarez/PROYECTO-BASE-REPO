<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'area_id',
    ];

    public function dependency(){
        return $this->belongsTo(Area::class, 'area_id');
    }
}
