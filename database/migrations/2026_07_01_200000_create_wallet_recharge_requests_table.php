<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wallet_recharge_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'fulfilled', 'rejected'])->default('pending');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('wallet_recharge_requests'); }
};
