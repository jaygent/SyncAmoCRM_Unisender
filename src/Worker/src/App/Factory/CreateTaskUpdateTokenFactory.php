<?php

namespace Worker\App\Factory;

use Psr\Container\ContainerInterface;
use Worker\App\Handler\CreateTaskUpdateToken;
use Worker\App\Helpers\BeansConfig;

/**
 * Class factory CreateTaskUpdateToken
 */
class CreateTaskUpdateTokenFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \Worker\App\Handler\CreateTaskUpdateToken
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CreateTaskUpdateToken
    {
        return new CreateTaskUpdateToken($container->get(BeansConfig::class));
    }
}
