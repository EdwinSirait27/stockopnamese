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
      Schema::create('mtoko_soglo', function (Blueprint $table) {
            $table->id();
               $table->string('kdtoko')->nullable();
            $table->string('kettoko')->nullable();
            $table->integer('masuk')->nullable();
            $table->string('personil')->nullable();
            $table->string('inputmasuk')->nullable();
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
        Schema::dropIfExists('mtoko_soglo');
    }
};
