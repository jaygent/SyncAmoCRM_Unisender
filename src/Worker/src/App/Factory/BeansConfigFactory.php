<?php

namespace Worker\App\Factory;

use Psr\Container\ContainerInterface;
use Worker\App\Helpers\BeansConfig;

/**
 * Cla
 */
class BeansConfigFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \Worker\App\Helpers\BeansConfig
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): BeansConfig
    {
        return new BeansConfig($container);
    }
}
