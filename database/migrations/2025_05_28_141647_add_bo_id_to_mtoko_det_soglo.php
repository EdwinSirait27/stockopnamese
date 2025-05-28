<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtoko_det_soglo', function (Blueprint $table) {
           $table->unsignedBigInteger('bo_id')->nullable()->after('BARCODE');
            $table->foreign('bo_id')
                ->references('id')
                ->on('bo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtoko_det_soglo', function (Blueprint $table) {
            //
        });
    }
};
