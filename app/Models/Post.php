<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str as SupportStr;
use Pest\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'featured_image',
        'excerpt',
        'content',
        'status',
    ];
    public function casts()
    {
        return [
            'published_at' => 'datetime',
        ];

    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
            $post->slug = SupportStr::slug($post->title);
            }
        });
    }
}
