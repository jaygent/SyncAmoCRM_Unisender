<?php

namespace Worker\App\Helpers;

use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerInterface;

/**
 * class
 */
class BeansConfig
{
    private ?Pheanstalk $connection;

    private array $config;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
            $this->config = $container->get('config')['beanstalk'];
            $this->connection = Pheanstalk::create(
                $this->config['host'],
                $this->config['port'],
                $this->config['timeout']
            );
    }

    public function getConnection(): ?Pheanstalk
    {
        return $this->connection;
    }
}
