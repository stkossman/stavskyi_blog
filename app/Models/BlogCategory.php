<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    use HasFactory;

    const ROOT = 1; // Додано

    protected $fillable
        = [
            'title',
            'slug',
            'parent_id',
            'description',
        ];

    /**
     * Батьківська категорія.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    /**
     *
     * @url https://laravel.com/docs/7.x/eloquent-mutators
     *
     * @return string
     */
    public function getParentTitleAttribute(): string
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
                ? 'Корінь'
                : '???');

        return $title;
    }

    /**
     * Перевірка, чи об'єкт є кореневим.
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->id === self::ROOT;
    }

     protected $casts = [
         'parent_id' => 'integer',
     ];
}
