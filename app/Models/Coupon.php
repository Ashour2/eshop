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

    public function calcDiscount(float $total): float
    {
        if ($this->type === 'percentage') {
            return round($total * ($this->value / 100), 2);
        }
        return min($this->value, $total);
    }

    public function isValid(float $total): array
    {
        if (!$this->active) {
            return ['valid' => false, 'message' => __('shop.coupon_inactive')];
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => __('shop.coupon_expired')];
        }
        if ($this->max_uses > 0 && $this->used_count >= $this->max_uses) {
            return ['valid' => false, 'message' => __('shop.coupon_exhausted')];
        }
        if ($total < $this->min_order) {
            return ['valid' => false, 'message' => __('shop.coupon_min_order', ['amount' => number_format($this->min_order, 2)])];
        }
        return ['valid' => true, 'message' => __('shop.coupon_applied')];
    }

    public function getDescriptionAttribute(): string
    {
        return $this->type === 'percentage'
            ? __('shop.coupon_desc_percent', ['value' => $this->value])
            : __('shop.coupon_desc_fixed', ['value' => number_format($this->value, 2)]);
    }
}
