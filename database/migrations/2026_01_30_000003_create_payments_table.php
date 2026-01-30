<?php

use App\Models\Order;
use App\Models\Payment;
use App\Enums\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create((new Payment())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->string('payment_method'); 
            $table->enum('status', StatusEnum::getPaymentStatuses())->default(StatusEnum::PENDING->value);
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Payment())->getTable());
    }
};
