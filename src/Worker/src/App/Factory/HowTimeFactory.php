<?php

namespace Worker\App\Factory;

use Psr\Container\ContainerInterface;
use Worker\App\Handler\Howtime;
use Worker\App\Helpers\BeansConfig;

/**
 * Cla
 */
class HowTimeFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \Worker\App\Handler\Howtime
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Howtime
    {
        return new Howtime($container->get(BeansConfig::class));
    }
}
