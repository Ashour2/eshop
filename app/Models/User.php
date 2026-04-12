<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'is_admin'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function wishlist() {
        return $this->hasMany(Wishlist::class);
    }

    // هل المنتج في المفضلة؟
    public function hasInWishlist(int $productId): bool {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    // عدد المفضلة
    public function wishlistCount(): int {
        return $this->wishlist()->count();
    }
}
