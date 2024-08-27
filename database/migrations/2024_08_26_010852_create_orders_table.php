<?php

use App\Enums\DeliveryStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\User;
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
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->decimal('product_price', 10, 2);
            $table->decimal('delivery_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->enum('status', OrderStatusEnum::getAllCases());
            $table->enum('payment_status', PaymentStatusEnum::getAllCases());
            $table->enum('delivery_status', DeliveryStatusEnum::getAllCases());
            $table->softDeletes();
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
