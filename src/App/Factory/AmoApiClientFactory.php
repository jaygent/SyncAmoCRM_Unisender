<?php

namespace App\Factory;

use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;

/**
 * AmoApiClientFactory создание клиента амо
 */
class AmoApiClientFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \AmoCRM\Client\AmoCRMApiClient
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AmoCRMApiClient
    {
        $config = $container->get('config')['amocrmapi'];
        return new AmoCRMApiClient($config['clientId'], $config['clientSecret'], $config['redirectUri']);
    }
}
