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
            $table->foreignId('generic_id')->nullable()->after('generic_name')->constrained()->onDelete('set null');
            $table->foreignId('manufacturer_id')->nullable()->after('manufacturer')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['generic_id']);
            $table->dropForeign(['manufacturer_id']);
            $table->dropColumn(['generic_id', 'manufacturer_id']);
        });
    }
};
