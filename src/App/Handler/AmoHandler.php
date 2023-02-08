<?php

declare(strict_types=1);

namespace App\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Helpers\Token;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Авторизация пользователя, сохранение в токенов
 */
class AmoHandler implements RequestHandlerInterface
{
    /**
     * @var \AmoCRM\Client\AmoCRMApiClient
     */
    private AmoCRMApiClient $AmoClient;

    /**
     * @param \AmoCRM\Client\AmoCRMApiClient $AmoClient
     */
    public function __construct(AmoCRMApiClient $AmoClient)
    {
        $this->AmoClient = $AmoClient;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \AmoCRM\Exceptions\BadTypeException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (isset($request->getQueryParams()['referer'])) {
            $this->AmoClient->setAccountBaseDomain($request->getQueryParams()['referer']);
        }
        if (!isset($request->getQueryParams()['code'])) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth2state'] = $state;
            if (isset($_GET['button'])) {
                echo $this->AmoClient->getOAuthClient()->getOAuthButton(
                    [
                        'title' => 'Установить интеграцию',
                        'compact' => true,
                        'class_name' => 'className',
                        'color' => 'default',
                        'error_callback' => 'handleOauthError',
                        'state' => $state,
                    ]
                );
                die;
            } else {
                $authorizationUrl = $this->AmoClient->getOAuthClient()->getAuthorizeUrl([
                    'state' => $state,
                    'mode' => 'post_message',
                ]);
                header('Location: ' . $authorizationUrl);
                die;
            }
        }
        try {
            $accessToken = $this->AmoClient->getOAuthClient()->getAccessTokenByCode($request->getQueryParams()['code']);
            if (!$accessToken->hasExpired()) {
                $this->AmoClient->setAccessToken($accessToken);
                $account = $this->AmoClient->account()->getCurrent();
                Token::setToken([
                    'access_token' => $accessToken->getToken(),
                    'refresh_token' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $this->AmoClient->getAccountBaseDomain(),
                    'clientId' => $account->getId() ?? 0,
                ]);
            }
        } catch (AmoCRMMissedTokenException $e) {
            Token::deletToken('0');
            error_log($e->getMessage()."\n", 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log');
            die();
        } catch (AmoCRMoAuthApiException|AmoCRMApiException $e) {
            error_log($e->getMessage()."\n", 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log');
        }
        return new JsonResponse(['code' => 200]);
    }
}
