<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $array = [
            'images/cars/toyotaw.png',
            'images/cars/toyotaG.png',
            'images/cars/yarisR.png',
            'images/cars/yarisB.png',
            'images/cars/toyotaBL.png',
            'images/cars/rev4W.png',
            'images/cars/civicG.png',
            'images/cars/civicBL.png',
            'images/cars/fitR.png',
            'images/cars/fitW.png',
            'images/cars/hrvB.png',
            'images/cars/hrvG.png',
            'images/cars/focusW.png',
            'images/cars/focusBL.png',
            'images/cars/fiestaR.png',
            'images/cars/fiestaB.png',
            'images/cars/ecoBL.png',
            'images/cars/ecoW.png',
            'images/cars/golfG.png',
            'images/cars/golfBL.png',
            'images/cars/poloR.png',
            'images/cars/poloG.png',
            'images/cars/tiguanB.png',
            'images/cars/tiguanBL.png',
            'images/cars/clioW.png',
            'images/cars/clioB.png',
            'images/cars/capturBL.png',
            'images/cars/capturR.png',
            'images/cars/meganeG.png',
            'images/cars/meganeB.png',
        ];

        for ($i = 0; $i < count($array); $i++) {
            DB::table('bens_locaveis')
                ->where('id', $i + 1) // ID starts from 1
                ->update(['imagem' => $array[$i]]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            DB::table('bens_locaveis')
                ->where('id', $i)
                ->update(['imagem' => null]);
        }
    }
};
