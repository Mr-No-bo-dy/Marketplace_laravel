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
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('id_comment', 'id_review');
        });

        Schema::rename('comments', 'reviews');

        Schema::table('reviews', function (Blueprint $table) {
            $table->integer('status')->default(2)->comment('1 = disapproved, 2 = approved')->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::rename('reviews', 'comments');

        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('id_review', 'id_comment');
        });
    }
};
