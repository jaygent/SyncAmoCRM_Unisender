<?php

declare(strict_types=1);

namespace Worker\App\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Helpers\Token;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Pheanstalk\Contract\PheanstalkInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Worker\App\Helpers\BaseWorker;
use Worker\App\Helpers\BeansConfig;

/**
 * Class Worker
 */
class TaskUpdateTokenWorker extends BaseWorker
{
    /**
     * @var string
     */
    protected string $queue = 'update_token';
    /**
     * @var \AmoCRM\Client\AmoCRMApiClient
     */
    private AmoCRMApiClient $AmoClient;

    /**
     * @param \Worker\App\Helpers\BeansConfig $pheanstalk
     * @param \AmoCRM\Client\AmoCRMApiClient $AmoClient
     */
    public function __construct(BeansConfig $pheanstalk, AmoCRMApiClient $AmoClient)
    {
        parent::__construct($pheanstalk);
        $this->AmoClient = $AmoClient;
    }

    /**
     * @param $data
     */
    public function process($data)
    {
        $accessToken = new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires' => $data->expires,
            'baseDomain' => $data->baseDomain
        ]);
        try {
            $this->AmoClient->getOAuthClient()->setBaseDomain($data->baseDomain)
                ->setAccessTokenRefreshCallback(
                    function (AccessTokenInterface $accessToken, string $baseDomain) use ($data) {
                        Token::setToken([
                            'access_token' => $accessToken->getToken(),
                            'refresh_token' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                            'clientId' => $data->clientId,
                        ]);
                    }
                )
                ->getAccessTokenByRefreshToken($accessToken);
            echo 'Token update'.$data->clientId;
        } catch (AmoCRMoAuthApiException $e) {
            var_dump($e->getMessage());
        }
    }
}
