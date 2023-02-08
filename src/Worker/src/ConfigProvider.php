<?php

declare(strict_types=1);

namespace Worker;

use AmoCRM\Client\AmoCRMApiClient;
use App\Factory\AmoApiClientFactory;
use Worker\App\Factory\BeansConfigFactory;
use Worker\App\Factory\CreateTaskUpdateTokenFactory;
use Worker\App\Factory\HowTimeFactory;
use Worker\App\Factory\TaskUpdateTokenFactory;
use Worker\App\Factory\TimeWorkerFactory;
use Worker\App\Handler\CreateTaskUpdateToken;
use Worker\App\Handler\Howtime;
use Worker\App\Handler\TaskUpdateTokenWorker;
use Worker\App\Handler\TimeWorker;
use Worker\App\Helpers\BeansConfig;

/**
 * The configuration provider for the Worker module
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
            'laminas-cli' => $this->getCliConfig(),
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the CLI configuration
     * @return array[]
     */
    public function getCliConfig(): array
    {
        return [
            'commands' => [
                'how-time' => \Worker\App\Handler\Howtime::class,
                'worker:time' => \Worker\App\Handler\TimeWorker::class,
                'worker:update-token' => TaskUpdateTokenWorker::class,
                'update-token'=> \Worker\App\Handler\CreateTaskUpdateToken::class,
            ],
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
                BeansConfig::class => BeansConfigFactory::class,
                Howtime::class => HowTimeFactory::class,
                TimeWorker::class => TimeWorkerFactory::class,
                CreateTaskUpdateToken::class => CreateTaskUpdateTokenFactory::class,
                AmoCRMApiClient::class => AmoApiClientFactory::class,
                TaskUpdateTokenWorker::class => TaskUpdateTokenFactory::class,
            ],
        ];
    }
}
