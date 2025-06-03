<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\BaseController as GuestBaseController;

abstract class BaseController extends GuestBaseController
{
    public function __construct()
    {
        //Ініціалізація загальних елементів адмінки
        // Тут можуть бути спільні для всіх адмін-контролерів дії,
        // наприклад, перевірка авторизації або ініціалізація сервісів.
    }
}
