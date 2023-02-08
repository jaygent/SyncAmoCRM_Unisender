<?php

namespace App\Factory;

use App\Helpers\ResourceContacts;
use Psr\Container\ContainerInterface;

/**
 * Класс создание ресурсов
 */
class ResourceContactFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return \App\Helpers\ResourceContacts
     */
    public function __invoke(ContainerInterface $container): ResourceContacts
    {
        return new ResourceContacts();
    }
}
