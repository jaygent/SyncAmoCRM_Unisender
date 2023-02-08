<?php

declare(strict_types=1);

namespace App;

use AmoCRM\Client\AmoCRMApiClient;
use App\Helpers\AmoAuth;
use App\Helpers\ResourceContacts;
use App\Helpers\Sync;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [

            ],
            'factories' => [
                AmoCRMApiClient::class => Factory\AmoApiClientFactory::class,
                Sync::class => Factory\SyncHandlerFactory::class,
                AmoAuth::class => Factory\AmoAuthFactory::class,
                ResourceContacts::class => Factory\ResourceContactFactory::class,
                Handler\AmoHandler::class => Factory\AmoPageFactory::class,
                Handler\AmoWidget::class => Factory\AmoWidgetFactory::class,
                Handler\AmoHookHandler::class => Factory\AmoHookFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app' => ['templates/app'],
                'error' => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
