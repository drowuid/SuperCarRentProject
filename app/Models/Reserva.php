<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BemLocavel;

class Reserva extends Model
{
    protected $fillable = [
        'bem_locavel_id',
        'nome_cliente',
        'email',
        'data_inicio',
        'data_fim',
        'payment_method',
        'payment_status',
        'atm_reference',
        'entidade',
        'referencia',
        'user_id',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function carro()
    {
        return $this->belongsTo(BemLocavel::class, 'bem_locavel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

