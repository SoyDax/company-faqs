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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            // columna id departamento que va a estar enlazada gracias a la
            // llave foranea con la tabla departamentos
            // el ID del departamento va a estar unido a la tabla departamentos.
            $table->foreignId('department_id')
            // Creamos la relacion entre tablas en Laravel 
            // Creamos un campo que va a ser la llave foranea y lo relacionamos
            // a la llave primaria de la otra tabla
            ->constrained('departments')
            // no va a permitir eliminar un departamento si es que hay empleados 
            // que pertenezcan a ese departamento
            ->onUpdate('cascade')->onDelete('restrict');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
