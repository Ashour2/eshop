<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name', 'slug', 'icon', 'name_ar', 'name_en'];

    public function products() {
        return $this->hasMany(Product::class);
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
}
