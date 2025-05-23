<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $table = 'caracteristicas';
    public $timestamps = false;

    protected $fillable = ['nome'];

    public function bens()
    {
        return $this->belongsToMany(BemLocavel::class, 'bem_caracteristicas', 'caracteristica_id', 'bem_locavel_id');
    }
}
