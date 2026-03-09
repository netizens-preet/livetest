<?php

namespace App\Models;
use App\Enums\PostStatus;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
   use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'published_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => PostStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        // $user = Auth::user();
        // $panel = $panel->getId();

        // if($panel == 'admin'){
        //     dump('panel:' . $panel);
        //     dd($user->toArray());
        //     return $user->role == Role::Admin;
        // }

        // return false;
        return true;
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }


        });
    }
}
