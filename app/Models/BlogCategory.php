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
        // Категорія належить іншій категорії (батьківській)
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    /**
     * Приклад аксесора (Accessor):
     * Повертає назву батьківської категорії або "Корінь"/"???".
     *
     * @url https://laravel.com/docs/7.x/eloquent-mutators
     *
     * @return string
     */
    public function getParentTitleAttribute(): string
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot() // Якщо parentCategory не існує (null)
                ? 'Корінь' // І це коренева категорія (ID 1)
                : '???'); // Інакше (невідома батьківська або помилка)

        return $title;
    }

    /**
     * Перевірка, чи об'єкт є кореневим.
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->id === self::ROOT; // Використовуємо self::ROOT
    }
}
