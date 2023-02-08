<?php

namespace App\Helpers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Exception\NotClientIdException;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Класс авторизации и обновления токенов
 */
class AmoAuth
{
    /**
     * @var \AmoCRM\Client\AmoCRMApiClient
     */
    protected AmoCRMApiClient $AmoClient;
    /**
     * @var string
     */
    protected string $account_id;

    /**
     * @param \AmoCRM\Client\AmoCRMApiClient $AmoClient
     */
    public function __construct(AmoCRMApiClient $AmoClient)
    {
        $this->AmoClient = $AmoClient;
    }

    /**
     * @param $account_id
     * @return \App\Helpers\AmoAuth
     */
    public function setAccountId($account_id): self
    {
        $this->account_id = $account_id;
        return $this;
    }

    /**
     * @return \AmoCRM\Client\AmoCRMApiClient
     * @throws \App\Exception\NotClientIdException
     */
    public function getAmoClientWithToken(): AmoCRMApiClient
    {
        if ($this->account_id != null) {
            $accessToken = Token::getToken($this->account_id);
            $this->AmoClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
                ->onAccessTokenRefresh(
                    function (AccessTokenInterface $accessToken, string $baseDomain) {
                        Token::setToken([
                            'access_token' => $accessToken->getToken(),
                            'refresh_token' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]);
                    }
                );
            return $this->AmoClient;
        }
        throw new NotClientIdException('Не получен id пользоваеля');
    }
}
