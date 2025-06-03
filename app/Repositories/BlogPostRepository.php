<?php

namespace App\Repositories;

use App\Models\BlogPost as Model; // Імпортуємо модель BlogPost
use Illuminate\Database\Eloquent\Collection; // Для підказки типів (можна залишити, хоч і не використовується напряму)
use Illuminate\Contracts\Pagination\LengthAwarePaginator; // Для підказки типів

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    /**
     * Повертає повне ім'я класу моделі, з якою працює репозиторій.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class; // Абстрагування моделі BlogPost
    }

    /**
     * Отримати список статей з пагінацією.
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC') // Сортуємо за ID у зворотньому порядку
            ->with([ // Додаємо жадібне завантаження
                'category' => function ($query) { // Завантажуємо категорію, вибираючи лише id та title
                    $query->select(['id', 'title']);
                },
                // 'category:id,title', // Альтернативний, коротший синтаксис для вибору стовпців
                'user:id,name', // Завантажуємо користувача, вибираючи лише id та name
            ])
            ->paginate(25);

        return $result;
    }

    /**
     * Отримати модель статті для редагування в адмінці за ID.
     *
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
