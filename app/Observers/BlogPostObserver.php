<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPostObserver
{
    /**
     * Обробка події "updating" (перед оновленням) запису.
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function updating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
    }

    /**
     * Обробка події "creating" (перед створенням) запису.
     * Цей метод додаємо, щоб логіка застосовувалась і при створенні нового посту.
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);

        // Встановлюємо user_id при створенні посту
        if (empty($blogPost->user_id)) {
            $blogPost->user_id = auth()->id() ?? 1; // Використовуємо ID поточного авторизованого користувача, або 1 за замовчуванням
        }
    }


    /**
     * Якщо поле published_at порожнє і is_published є true,
     * то генеруємо поточну дату.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setPublishedAt(BlogPost $blogPost): void
    {
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * Якщо псевдонім (slug) порожній,
     * то генеруємо псевдонім зі заголовка.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setSlug(BlogPost $blogPost): void
    {
        if (empty($blogPost->slug)) {
            $blogPost->slug = Str::slug($blogPost->title);
        }
    }
}
