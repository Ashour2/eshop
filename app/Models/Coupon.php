<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value',
        'min_order', 'max_uses',
        'used_count', 'active', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'active'     => 'boolean',
    ];

    // ── حساب قيمة الخصم ──────────────────────────────────
    public function calcDiscount(float $total): float
    {
        if ($this->type === 'percentage') {
            return round($total * ($this->value / 100), 2);
        }
        return min($this->value, $total); // لا يتجاوز إجمالي الطلب
    }

    // ── التحقق من صلاحية الكوبون ─────────────────────────
    public function isValid(float $total): array
    {
        if (!$this->active) {
            return ['valid' => false, 'message' => 'هذا الكوبون غير مفعّل'];
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'انتهت صلاحية هذا الكوبون'];
        }
        if ($this->max_uses > 0 && $this->used_count >= $this->max_uses) {
            return ['valid' => false, 'message' => 'تم استنفاد عدد استخدامات هذا الكوبون'];
        }
        if ($total < $this->min_order) {
            return ['valid' => false, 'message' => 'الحد الأدنى للطلب هو $' . number_format($this->min_order, 2)];
        }
        return ['valid' => true, 'message' => 'تم تطبيق الكوبون بنجاح!'];
    }

    // ── وصف نوع الخصم ────────────────────────────────────
    public function getDescriptionAttribute(): string
    {
        return $this->type === 'percentage'
            ? "خصم {$this->value}%"
            : "خصم $" . number_format($this->value, 2);
    }
}
