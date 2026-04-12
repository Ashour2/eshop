<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 8, 2);                    // نسبة % أو مبلغ ثابت
            $table->decimal('min_order', 8, 2)->default(0);    // الحد الأدنى للطلب
            $table->integer('max_uses')->default(0);           // 0 = بلا حد
            $table->integer('used_count')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('coupons'); }
};
