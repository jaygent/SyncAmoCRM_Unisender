<?php

namespace Worker\App\Factory;

use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;
use Worker\App\Handler\CreateTaskUpdateToken;
use Worker\App\Handler\TaskUpdateTokenWorker;
use Worker\App\Helpers\BeansConfig;

/**
 * Class factory CreateTaskUpdateToken
 */
class TaskUpdateTokenFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \Worker\App\Handler\TaskUpdateTokenWorker
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TaskUpdateTokenWorker
    {
        return new TaskUpdateTokenWorker($container->get(BeansConfig::class), $container->get(AmoCRMApiClient::class));
    }
}
