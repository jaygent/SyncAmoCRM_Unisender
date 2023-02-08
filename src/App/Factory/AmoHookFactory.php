<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\AmoHookHandler;
use App\Helpers\AmoAuth;
use App\Helpers\ResourceContacts;
use App\Helpers\Sync;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Создание AmoHookHandler
 */
class AmoHookFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \Psr\Http\Server\RequestHandlerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new AmoHookHandler(
            $container->get(Sync::class),
            $container->get(AmoAuth::class),
            $container->get(ResourceContacts::class)
        );
    }
}
