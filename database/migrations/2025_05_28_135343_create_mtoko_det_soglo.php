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
       Schema::create('mtoko_det_soglo', function (Blueprint $table) {
            $table->id();
              $table->string('KDTOKO')->nullable();
            $table->string('BARA')->nullable();
            $table->integer('NOURUT')->nullable();
            $table->double('FISIK')->nullable();
            $table->string('BARCODE')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtoko_det_soglo');
    }
};
