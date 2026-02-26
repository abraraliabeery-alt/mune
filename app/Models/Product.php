<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $fillable = [
        'name',
        'name_en',
        'name_ar',
        'category',
        'price',
        'description',
        'description_en',
        'description_ar',
        'image_url',
        'is_available',
    ];

    public function displayName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        $value = $locale === 'ar' ? (string) ($this->name_ar ?? '') : (string) ($this->name_en ?? '');

        if (trim($value) !== '') {
            return $value;
        }

        return (string) ($this->name ?? '');
    }

    public function displayDescription(?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();

        $value = $locale === 'ar' ? (string) ($this->description_ar ?? '') : (string) ($this->description_en ?? '');

        if (trim($value) !== '') {
            return $value;
        }

        $fallback = (string) ($this->description ?? '');

        return trim($fallback) !== '' ? $fallback : null;
    }
}
