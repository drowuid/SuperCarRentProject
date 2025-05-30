<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('bens_locaveis', function (Blueprint $table) {
        $table->boolean('is_available')->default(true);
    });
}


public function down(): void
{
    Schema::table('bens_locaveis', function (Blueprint $table) {
        $table->dropColumn('is_available');
    });
}

};
