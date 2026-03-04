<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'body_en',
        'body_ar',
        'cover_image_path',
        'is_published',
        'published_at',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function media()
    {
        return $this->hasMany(WorkMedia::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getDisplayTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ((string) ($this->title_ar ?: $this->title_en))
            : ((string) ($this->title_en ?: $this->title_ar));
    }

    public function getDisplayBodyAttribute(): string
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ((string) ($this->body_ar ?: $this->body_en))
            : ((string) ($this->body_en ?: $this->body_ar));
    }
}
