<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit']);          // credit = شحن، debit = خصم
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);            // الرصيد بعد العملية
            $table->string('source_type')->nullable();           // 'redeem_code' | 'service_purchase' | 'admin_adjustment'
            $table->unsignedBigInteger('source_id')->nullable(); // id الكود أو الطلب
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('wallet_transactions'); }
};
