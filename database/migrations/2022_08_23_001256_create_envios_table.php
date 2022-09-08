<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnviosTable extends Migration
{
    
    public function up()
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_producto');
            $table->string('ciudad_origen',30);
            $table->string('ciudad_destino',30);
            $table->integer('peso');
            $table->string('tipo_envio');
            $table->integer('alto')->nullable();
            $table->integer('ancho')->nullable();
            $table->integer('largo')->nullable();
            $table->integer('precio')->nullable();
            $table->string('transportadora',30)->nullable();
            $table->foreign('id_producto')->references('id')->on('productos');
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
        Schema::dropIfExists('envios');
    }
}
