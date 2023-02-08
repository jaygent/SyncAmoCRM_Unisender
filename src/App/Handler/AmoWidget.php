<?php

declare(strict_types=1);

namespace App\Handler;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Exception\NotClientIdException;
use App\Helpers\AmoAuth;
use App\Helpers\IsValidApikeyUnisender as IsValid;
use App\Helpers\Sync;
use App\Helpers\Token;
use App\Model\Users;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Получение контактов из amocrm
 */
class AmoWidget implements RequestHandlerInterface
{
    /**
     * @var \App\Helpers\Sync
     */
    private Sync $sync;
    /**
     * @var \App\Helpers\AmoAuth
     */
    private AmoAuth $AmoClient;

    /**
     * @param \App\Helpers\Sync $sync
     * @param \App\Helpers\AmoAuth $AmoClient
     */
    public function __construct(Sync $sync, AmoAuth $AmoClient)
    {
        $this->sync = $sync;
        $this->AmoClient = $AmoClient;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = urldecode(parse_url($_SERVER['REQUEST_URI'])['query']);
        $data = json_decode($data);
        if (Isvalid::testapi($data)) {
            $user = Users::find($data->account_id);
            $user->unisender_api_key = $data->token;
            $user->save();
            try {
                $webHookModel = (new \AmoCRM\Models\WebhookModel())
                    ->setSettings(['add_contact', 'update_contact', 'delete_contact'])
                    ->setDestination('https://ff3d-173-233-147-68.eu.ngrok.io/webhook');
                $Amoclient = $this->AmoClient->setAccountId($data->account_id)
                    ->getAmoClientWithToken();
                $response = $Amoclient->webhooks()->subscribe($webHookModel)->toArray();
                $this->sync->setAmoClient($Amoclient)
                    ->setUnisenderkey($data->token)
                    ->getContact()
                    ->importContact();
            } catch (NotClientIdException $e) {
                error_log($e->getMessage() . "\n", 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log');
                die();
            } catch (AmoCRMApiNoContentException $e) {
                die();
            } catch (AmoCRMoAuthApiException $e) {
                error_log(
                    $e->getMessage() . "\n",
                    3,
                    dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
                );
                Token::deletToken($data->account_id);
                die();
            } catch (AmoCRMMissedTokenException|AmoCRMApiException $e) {
                error_log($e->getMessage() . "\n", 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log');
                die();
            }
        } else {
            $user = Users::find($data->account_id);
            $user->unisender_api_key = null;
            $user->save();
        }
        return new JsonResponse(['code' => 200]);
    }
}
