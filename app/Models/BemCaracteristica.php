<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BemCaracteristica extends Pivot
{
    protected $table = 'bem_caracteristicas';
    public $timestamps = false;
}

