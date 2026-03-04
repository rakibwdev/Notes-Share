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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('pieces_per_strip')->default(10)->after('description');
            $table->unsignedInteger('pieces_per_box')->default(100)->after('pieces_per_strip');
            $table->decimal('price_per_piece', 10, 2)->default(0.00)->after('pieces_per_box');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['pieces_per_strip', 'pieces_per_box', 'price_per_piece']);
        });
    }
};
