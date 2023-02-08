<?php

namespace App\Factory;

use App\Helpers\Sync;
use Psr\Container\ContainerInterface;

/**
 * Собираеться класс синхронизации
 */
class SyncHandlerFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \App\Helpers\Sync

     */
    public function __invoke(ContainerInterface $container): Sync
    {
        return new Sync();
    }
}
