<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['customer_name', 'customer_email', 'customer_address', 'total', 'status'];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending'    => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped'    => 'تم الشحن',
            'delivered'  => 'تم التسليم',
            'cancelled'  => 'ملغي',
            default      => $this->status,
        };
    }
}
