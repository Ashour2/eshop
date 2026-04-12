<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price',
        'stock', 'image', 'active', 'category_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class)->where('approved', true)->latest();
    }

    public function getImageUrlAttribute(): string {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-image.png');
    }

    // متوسط التقييم
    public function getAvgRatingAttribute(): float {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // عدد التقييمات
    public function getReviewsCountAttribute(): int {
        return $this->reviews()->count();
    }

    // نجوم HTML
    public function getStarsHtmlAttribute(): string {
        $avg  = $this->avg_rating;
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($avg >= $i)       $html .= '<i class="bi bi-star-fill text-warning"></i>';
            elseif ($avg >= $i-0.5) $html .= '<i class="bi bi-star-half text-warning"></i>';
            else                  $html .= '<i class="bi bi-star text-warning"></i>';
        }
        return $html;
    }
}
