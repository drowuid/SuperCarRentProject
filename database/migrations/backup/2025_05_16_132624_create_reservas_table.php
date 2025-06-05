<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
    $table->id(); // this is unsignedBigInteger, but it's your PK so ok
    $table->integer('bem_locavel_id'); // SIGNED int to match bens_locaveis.id
    $table->string('nome_cliente');
    $table->string('email');
    $table->date('data_inicio');
    $table->date('data_fim');
    $table->timestamps();

    $table->foreign('bem_locavel_id')
          ->references('id')
          ->on('bens_locaveis')
          ->onDelete('cascade');
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
