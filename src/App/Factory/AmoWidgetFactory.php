<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\AmoWidget;
use App\Helpers\AmoAuth;
use App\Helpers\Sync;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * AmoWidget
 */
class AmoWidgetFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \Psr\Http\Server\RequestHandlerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new AmoWidget($container->get(Sync::class), $container->get(AmoAuth::class));
    }
}
