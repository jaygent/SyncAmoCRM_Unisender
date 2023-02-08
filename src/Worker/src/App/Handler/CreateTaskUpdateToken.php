<?php

declare(strict_types=1);

namespace Worker\App\Handler;

use App\Model\Users;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Worker\App\Helpers\BeansConfig;

/**
 * Class add time queue
 */
class CreateTaskUpdateToken extends Command
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

    protected function configure(): void
    {
        $this->addOption('time', 't', InputOption::VALUE_REQUIRED);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $time = (int)$input->getOption('time');
        if ($time != null && is_int(mktime($time))) {
            $users = Users::all();
            foreach ($users as $user) {
                if (mktime($time) >= $user->expires) {
                    $data = [
                        'access_token' => $user->access_token,
                        'refresh_token' => $user->refresh_token,
                        'baseDomain' => $user->baseDomain,
                        'expires' => $user->expires,
                        'clientId' => $user->clientId,
                    ];
                    $this->connection->useTube('update_token')->put(json_encode($data));
                    $output->writeln('Пользователь добавлен');
                }
            }
        } else {
            $output->writeln('Видите время проверки');
        }
        return 0;
    }
}
