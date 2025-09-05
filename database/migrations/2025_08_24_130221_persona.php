<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('name');
            $table->string('apellido');
            $table->string('CI');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('estadoCivil')->nullable();
            $table->string('genero')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('estadoRegistro');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
