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
        Schema::create('categories_attributes', function (Blueprint $table) {
            $table->id('id_categories_attribute');
            $table->foreignId('id_attribute')->constrained('attributes', 'id_attribute');
            $table->foreignId('id_category')->constrained('categories', 'id_category');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_attributes');
    }
};
