<?php

namespace App\Helpers;

/**
 * Класс проверки ключа unisender
 */
class IsValidApikeyUnisender
{
    /**
     * @param $data
     * @return bool
     */
    public static function testapi($data): bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.unisender.com/ru/api/getLists?format=json&api_key={$data->token}");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $result = json_decode($response,true);
        curl_close($ch);
        if (!empty($result['result'])) {
            return true;
        }
        error_log(date('d-m-Y-H-i-s')."--{$data->account_id}-invalid_api_key-- {$data->token} unisender" . '\n', 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log');
        return false;
    }

}