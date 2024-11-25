<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('noites_de_sono', function (Blueprint $table) {
            $table->unique(['usuario_id', 'data']);
        });
    }
    
    public function down()
    {
        Schema::table('noites_de_sono', function (Blueprint $table) {
            $table->dropUnique(['usuario_id', 'data']);
        });
    }
};
