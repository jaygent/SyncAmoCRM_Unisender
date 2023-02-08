<?php

declare(strict_types=1);

namespace Worker\App\Handler;

use Carbon\Carbon;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Worker\App\Helpers\BeansConfig;

/**
 * Class add time queue
 */
class Howtime extends Command
{
    /**
     * @var \Pheanstalk\Pheanstalk
     */
    protected Pheanstalk $connection;

    /**
     * @param \Worker\App\Helpers\BeansConfig|null $connection
     */
    public function __construct(BeansConfig $connection)
    {
        parent::__construct();
        $this->connection = $connection->getConnection();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = Carbon::now()->format(' H:i  (m.Y)');
        $this->connection->useTube('time')->put(json_encode($data));
        return 0;
    }
}
