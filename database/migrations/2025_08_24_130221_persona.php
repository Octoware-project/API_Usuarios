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
            $table->unsignedBigInteger('user_id'); // Relación con users

            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('CI')->nullable();
            $table->string('Telefono')->nullable();
            $table->string('Direccion')->nullable();
            $table->string('Estado_Registro')->nullable();
            $table->timestamps();

            // Clave foránea con users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
