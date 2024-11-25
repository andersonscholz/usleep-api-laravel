<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoitesDeSonoTable extends Migration
{
    public function up()
    {
        Schema::create('noites_de_sono', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id'); // Chave estrangeira para a tabela 'usuarios'
            $table->date('data'); // Data da noite de sono
            $table->integer('horas_dormidas'); // Quantidade de horas dormidas
            $table->timestamps();

            // Definir a chave estrangeira
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('noites_de_sono');
    }
}
