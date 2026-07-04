<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('redeem_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_used')->default(false);
            $table->boolean('is_disabled')->default(false);     // للتعطيل اليدوي من الأدمن
            $table->foreignId('used_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('batch_note')->nullable();            // ملاحظة الدفعة
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('redeem_codes'); }
};
