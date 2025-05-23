<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoBem extends Model
{
    protected $table = 'tipo_bens';
    public $timestamps = false;

    protected $fillable = ['nome'];

    public function marcas()
    {
        return $this->hasMany(Marca::class, 'tipo_bem_id');
    }
}

