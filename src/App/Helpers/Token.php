<?php

namespace App\Helpers;

use App\Model\Contacts;
use App\Model\Users;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Сохранение и получение Токенов из amocrm
 */
class Token
{
    /**
     * @param array $data
     * @return void
     */
    public static function setToken(array $data): void
    {
        Users::updateOrCreate(
            [
                'clientId' => $data['clientId']
            ],
            [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'baseDomain' => $data['baseDomain'],
                'expires' => $data['expires'],
            ]
        );
    }

    /**
     * @return array
     */
    public static function getToken(?string $id): AccessToken
    {
        $user = Users::find($id);
        return new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $user->access_token,
            'refresh_token' => $user->refresh_token,
            'expires' => $user->expires,
            'baseDomain' => $user->baseDomain
        ]);
    }

    /**
     * @param string|null $id
     * @return void
     */
    public static function deletToken(?string $id): void
    {
        Users::destroy($id);
        Contacts::where('account_id', $id)->delete();
    }
}
