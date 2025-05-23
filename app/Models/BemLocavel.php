<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;
use App\Models\Localizacao;


class BemLocavel extends Model
{
    protected $table = 'bens_locaveis';
    public $timestamps = false; // If your table doesn't have created_at/updated_at

    protected $fillable = [
    'marca_id', 'modelo', 'imagem', 'registo_unico_publico', 'cor',
    'numero_passageiros', 'combustivel', 'numero_portas',
    'transmissao', 'ano', 'manutencao', 'preco_diario', 'observacao'
];


    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function localizacoes()
    {
        return $this->hasMany(Localizacao::class, 'bem_locavel_id');
    }

    public function caracteristicas()
{
    return $this->belongsToMany(Caracteristica::class, 'bem_caracteristicas', 'bem_locavel_id', 'caracteristica_id');
}

}

