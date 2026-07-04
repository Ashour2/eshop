<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_address',
        'total',
        'status',
        'coupon_code',
        'discount',
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string {
        $key = 'shop.status_' . $this->status;
        $label = __($key);
        return $label !== $key ? $label : $this->status;
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }
}
