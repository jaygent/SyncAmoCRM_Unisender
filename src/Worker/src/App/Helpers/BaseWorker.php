<?php

namespace Worker\App\Helpers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/***
 * Abstract BaseWorker
 */
abstract class BaseWorker extends Command
{
    /**
     * @var \Pheanstalk\Pheanstalk
     */
    protected Pheanstalk $connection;
    /**
     * @var string
     */
    protected string $queue = 'default';

    /**
     * @param \Worker\App\Helpers\BeansConfig $pheanstalk
     */
    public function __construct(BeansConfig $pheanstalk)
    {
        parent::__construct();
        $this->connection = $pheanstalk->getConnection();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while ($job = $this->connection->watchOnly($this->queue)
            ->ignore(PheanstalkInterface::DEFAULT_TUBE)
            ->reserve()) {
            try {
                $this->process(json_decode($job->getData(), false, 512, JSON_THROW_ON_ERROR));
            } catch (\Throwable $exception) {
                $this->handleException($exception, $job);
            }
            $this->connection->delete($job);
        }
            echo 'Not found job';
        return 0;
    }

    /**
     * @param \Throwable $exception
     * @param \Pheanstalk\Job $job
     * @return void
     */
    protected function handleException(\Throwable $exception, Job $job)
    {
        error_log(
            'Error' . PHP_EOL . $job->getData(). "\n",
            3,
            dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
        );
    }

    /**
     * @param $data
     * @return mixed
     */
    abstract public function process($data);
}
