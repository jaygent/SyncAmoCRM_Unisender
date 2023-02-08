<?php

namespace Worker\App\Factory;

use Psr\Container\ContainerInterface;
use Worker\App\Handler\TimeWorker;
use Worker\App\Helpers\BeansConfig;

/**
 * Cla
 */
class TimeWorkerFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \App\Helpers\ResourceContacts
     */
    public function __invoke(ContainerInterface $container): TimeWorker
    {
        return new TimeWorker($container->get(BeansConfig::class));
    }
}
