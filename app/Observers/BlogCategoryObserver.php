<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryObserver
{
    /**
     * Обробка події "creating" (перед створенням) запису.
     *
     * @param  BlogCategory  $blogCategory
     * @return void
     */
    public function creating(BlogCategory $blogCategory): void
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Обробка події "updating" (перед оновленням) запису.
     *
     * @param  BlogCategory  $blogCategory
     * @return void
     */
    public function updating(BlogCategory $blogCategory): void
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Якщо псевдонім (slug) порожній,
     * то генеруємо псевдонім зі заголовка.
     *
     * @param BlogCategory  $blogCategory
     * @return void
     */
    protected function setSlug(BlogCategory $blogCategory): void
    {
        if (empty($blogCategory->slug)) {
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }
}
