<?php

declare(strict_types=1);

namespace Worker\App\Handler;

use Pheanstalk\Contract\PheanstalkInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Worker\App\Helpers\BaseWorker;

/**
 * Class Worker
 */
class TimeWorker extends BaseWorker
{

    protected string $queue = 'time';

    public function process($data)
    {
        echo $data;
    }
}
