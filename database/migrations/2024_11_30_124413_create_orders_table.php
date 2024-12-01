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
            $table->string('user_id');
            $table->date('order_date')->nullable();
            $table->string('order_number')->unique();
            $table->string('party_name')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('party_city')->nullable();
            $table->string('party_phone')->nullable();
            $table->string('series')->nullable();
            $table->string('code_no')->nullable();
            $table->string('size')->nullable()->comment('Format: ft x ft');
            $table->decimal('auto_rent', 10, 2)->nullable()->comment('Auto rent in digits');
            $table->decimal('vehicle_rent', 10, 2)->nullable()->comment('Vehicle rent in digits');
            $table->string('transport')->nullable();
            $table->string('paid_by')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->string('delivery_from')->nullable();
            $table->string('package_no')->nullable();
            $table->string('purchase_no')->nullable();
            $table->string('sell_bill_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('date')->nullable();
            $table->string('cash_received_by')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
