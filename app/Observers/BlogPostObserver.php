<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPostObserver
{
    /**
     * Обробка події "creating" (перед створенням) запису.
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost); // Додано
        $this->setUser($blogPost); // Додано
    }

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
        $this->setHtml($blogPost); // Додано
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

    /**
     * Встановлюємо значення полю content_html з поля content_raw.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setHtml(BlogPost $blogPost): void
    {
        if ($blogPost->isDirty('content_raw')) { // Перевіряємо, чи змінилося поле content_raw
            // Тут треба зробити генерацію markdown -> html
            // Для простоти зараз просто копіюємо content_raw
            $blogPost->content_html = $blogPost->content_raw;
        }
    }

    /**
     * Якщо user_id не вказано, то встановимо юзера 1 (UNKNOWN_USER).
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setUser(BlogPost $blogPost): void
    {
        // Використовуємо ID поточного авторизованого користувача,
        // або константу UNKNOWN_USER, якщо користувач не авторизований.
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }
}
