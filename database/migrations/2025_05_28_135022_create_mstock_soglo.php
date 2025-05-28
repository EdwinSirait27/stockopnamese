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
        Schema::create('mstock_soglo', function (Blueprint $table) {
            $table->id();
            $table->string('BARA')->nullable();
            $table->string('BARA2')->nullable();
            $table->string('NAMA')->nullable();
            $table->double('SALDO')->nullable();
            $table->double('AVER')->nullable();
            $table->string('SATUAN')->nullable();
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
        Schema::dropIfExists('mstock_soglo');
    }
};
