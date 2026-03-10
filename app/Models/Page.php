<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{

    use Searchable;

    public $timestamps = false;
    protected $fillable = [
        'label',
        'icon',
        'route',
        'order',
        'state',
        'page_id',
        'type',
        'permission_name'
    ];

    protected $appends = ['active'];

    public function parent() {
        return $this->belongsTo(Page::class,'page_id');
    }

    public function children() {
        return $this->hasMany(Page::class,'page_id');
    }

    public function getActiveAttribute() {
        return false;
    }

}
