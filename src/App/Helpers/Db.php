<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

/**
 * Класс создание Базы данных
 */
class Db
{
    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    private Capsule $capsule;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $capsule = new Capsule();
        $capsule->addConnection($config);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $this->capsule = $capsule;
    }

    /**
     * @return \Illuminate\Database\Capsule\Manager
     */
    public function getCapsule(): Capsule
    {
        return $this->capsule;
    }

}