<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CoreRepository.
 *
 * Репозиторій для роботи з сутністю.
 * Може видавати набори даних.
 * Не може змінювати та створювати сутності.
 */
abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * CoreRepository constructor
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass()); //app('App\Models\BlogCategory')
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass();

    /**
     * @return Model|\Illuminate\Foundation\Application|mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}
