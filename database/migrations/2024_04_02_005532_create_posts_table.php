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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories') 
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('sub_category_id')
                ->constrained('sub_categories') 
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('department_id')
                ->constrained('departments') 
                ->onUpdate('cascade')->onDelete('restrict');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
