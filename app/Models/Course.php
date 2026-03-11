<?php

namespace App\Models;

use App\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'Category',
        'level',
        'is_published',
        'thumbnail',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'level' => Level::class,
            'is_published' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
