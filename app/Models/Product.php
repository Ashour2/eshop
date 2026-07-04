<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'name_ar', 'name_en',
        'description', 'description_ar', 'description_en',
        'price', 'active', 'category_id',
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

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->attributes['name_' . $locale]
            ?? $this->attributes['name_ar']
            ?? $this->attributes['name']
            ?? '';
    }

    public function setNameArAttribute(string $value): void
    {
        $this->attributes['name_ar'] = $value;
        $this->attributes['name']    = $value;
    }

    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->attributes['description_' . $locale]
            ?? $this->attributes['description_ar']
            ?? $this->attributes['description']
            ?? null;
    }

    public function setDescriptionArAttribute(?string $value): void
    {
        $this->attributes['description_ar'] = $value;
        $this->attributes['description']    = $value;
    }

    public function getImageUrlAttribute(): string {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-image.png');
    }

    public function getAvgRatingAttribute(): float {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int {
        return $this->reviews()->count();
    }

    public function getStarsHtmlAttribute(): string {
        $avg  = $this->avg_rating;
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($avg >= $i)          $html .= '<i class="bi bi-star-fill text-warning"></i>';
            elseif ($avg >= $i-0.5) $html .= '<i class="bi bi-star-half text-warning"></i>';
            else                    $html .= '<i class="bi bi-star text-warning"></i>';
        }
        return $html;
    }
}
