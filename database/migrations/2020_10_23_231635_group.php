<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Group extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->string('espelho')->unique();
            $table->string('status');
            $table->string('anoformacao');
            $table->string('datasituacao');
            $table->string('ultimoenvio');
            $table->string('area');
            $table->string('uf');
            $table->string('telefone');
            $table->string('contato');
            $table->string('titulo')->unique();
            $table->string('lideres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
