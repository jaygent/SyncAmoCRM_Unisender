<?php

namespace App\Factory;

use AmoCRM\Client\AmoCRMApiClient;
use App\Helpers\AmoAuth;
use Psr\Container\ContainerInterface;

/**
 * Собираеться класс AmoAuth
 */
class AmoAuthFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \App\Helpers\AmoAuth
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AmoAuth
    {
        return new AmoAuth($container->get(AmoCRMApiClient::class));
    }
}
