<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\BlogCategory;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Атрибути, які можна масово призначати.
     *
     * @var array
     */
    protected $fillable
        = [
            'title',
            'slug',
            'category_id',
            'excerpt',
            'content_raw',
            'is_published',
            'published_at',
            'user_id',
        ];

    /**
     * Категорія статті.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        // Стаття належить категорії
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Автор статті.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Стаття належить користувачу
        return $this->belongsTo(User::class);
    }
}
