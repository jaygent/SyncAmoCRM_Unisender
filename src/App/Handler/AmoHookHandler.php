<?php

declare(strict_types=1);

namespace App\Handler;

use App\Exception\NotClientIdException;
use App\Helpers\AmoAuth;
use App\Helpers\ResourceContacts;
use App\Helpers\Sync;
use App\Model\Users;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AmoHookHandler
 */
class AmoHookHandler implements RequestHandlerInterface
{
    /**
     * @var \App\Helpers\Sync
     */
    private Sync $sync;
    /**
     * @var \App\Helpers\AmoAuth
     */
    private AmoAuth $amoAuth;
    /**
     * @var string
     */
    private string $unisenderapi;

    /**
     * @var \App\Helpers\ResourceContacts
     */
    private ResourceContacts $resourceContacts;

    /**
     * @param Sync $sync
     * @param \App\Helpers\AmoAuth $amoAuth
     * @param \App\Helpers\ResourceContacts $resourceContacts
     */
    public function __construct(Sync $sync, AmoAuth $amoAuth, ResourceContacts $resourceContacts)
    {
        $this->sync = $sync;
        $this->amoAuth = $amoAuth;
        $this->resourceContacts = $resourceContacts;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \AmoCRM\Exceptions\BadTypeException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!empty($request->getParsedBody())) {
            $data = $request->getParsedBody();
            $account_id = $data['account']['id'];
            $user = Users::find($account_id);
            $this->unisenderapi = $user->unisender_api_key ?? '';
            if (empty($this->unisenderapi)) {
                error_log(
                    "Некоректный api key unisender \n",
                    3,
                    dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
                );
                die();
            }
            foreach ($data['contacts'] as $key => $val) {
                $data = $this->resourceContacts->$key($val[0], $this->sync->setUnisenderkey($this->unisenderapi)->getOrCreateList());
                if ($data != null) {
                    try {
                        $this->sync->setAmoClient(
                            $this->amoAuth->setAccountId($account_id)
                                ->getAmoClientWithToken()
                        )->setUnisenderkey($this->unisenderapi)->setContact($data)->importContact();
                    } catch (NotClientIdException $e) {
                        error_log($e->getMessage() . "\n", 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log');
                        die();
                    }
                }
            }
        }
        return new JsonResponse(['code' => 200]);
    }
}
