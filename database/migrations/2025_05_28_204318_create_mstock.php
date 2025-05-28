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
        Schema::create('mstock', function (Blueprint $table) {
           $table->id();
            $table->string('BARA')->nullable();
            $table->string('BARA2')->nullable();
            $table->string('NAMA')->nullable();
            $table->double('SALDO')->nullable();
            $table->double('AVER')->nullable();
            $table->double('AWAL')->nullable();
            $table->double('MASUK')->nullable();
            $table->double('KELUAR')->nullable();
            $table->string('SATUAN')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mstock');
    }
};
