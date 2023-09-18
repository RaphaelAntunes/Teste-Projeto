<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Eventos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->string('titulo');
            $table->string('descricao');
            $table->boolean('status');
            $table->timestamp('data_inicio');
            $table->timestamp('data_prazo');
            $table->timestamp('data_conclusao')->nullable();
            $table->string('usr_responsavel');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
