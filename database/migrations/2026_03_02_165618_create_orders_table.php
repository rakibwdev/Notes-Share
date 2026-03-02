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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained('customers');
            $table->foreignId('delivery_man_id')->nullable()->index()->constrained('delivery_men')->nullOnDelete();
            $table->decimal('total_price', 12, 2);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->string('payment_method', 50);
            $table->string('status', 50)->index(); // Pending, Confirmed, Delivered, Cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
