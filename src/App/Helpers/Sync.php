<?php

declare(strict_types=1);

namespace App\Helpers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Model\Contacts;
use Unisender\ApiWrapper\UnisenderApi;

/**
 * Класс синхронизации
 *
 */
class Sync
{
    /**
     * @var \AmoCRM\Client\AmoCRMApiClient
     */
    private AmoCRMApiClient $AmoClient;

    /**
     * @var string
     */
    private string $Unisenderkey;
    /**
     * @var int
     */
    private string $account_id;
    /**
     * @var array
     */
    protected array $data;

    /**
     * @var array
     */
    protected array $field_names;

    /**
     * @param \AmoCRM\Client\AmoCRMApiClient $AmoClient
     * @return $this
     */
    public function setAmoClient(AmoCRMApiClient $AmoClient): self
    {
        $this->AmoClient = $AmoClient;
        return $this;
    }

    /**
     * @param $Unisenderke
     * @return \App\Helpers\Sync
     */
    public function setUnisenderkey($Unisenderke): self
    {
        $this->Unisenderkey = $Unisenderke;
        return $this;
    }

    /**
     * @return bool
     * @throws \AmoCRM\Exceptions\AmoCRMMissedTokenException
     * @throws \AmoCRM\Exceptions\AmoCRMApiException
     */

    public function getContact(): self
    {
        try {
            $contacts = $this->AmoClient->contacts()->get();
            $account = $this->AmoClient->account()->getCurrent();
            $this->account_id = (string)$account->getId();
        } catch (AmoCRMApiNoContentException $e) {
            error_log(
                "Нет данных" . $e->getMessage() . "\n",
                3,
                dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
            );
            $this->data = [];
        } catch (AmoCRMMissedTokenException $e) {
            error_log(
                'Токен не установлен' . $e->getMessage() . "\n",
                3,
                dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
            );
            throw new AmoCRMMissedTokenException($e->getMessage());
        } catch (AmoCRMoAuthApiException $e) {
            error_log(
                $e->getMessage() . "\n",
                3,
                dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
            );
            Token::deletToken($this->account_id);
            throw new AmoCRMoAuthApiException($e->getMessage());
        } catch (AmoCRMApiException $e) {
            error_log(
                $e->getMessage() . "\n",
                3,
                dirname($_SERVER['DOCUMENT_ROOT']) . '/errors.log'
            );
            throw new AmoCRMApiException($e->getMessage());
        }
        $email_list = $this->getOrCreateList();
        foreach ($contacts as $contact) {
            foreach ($contact->getCustomFieldsValues()->all() as $fild) {
                if ($fild->fieldName === 'Email') {
                    foreach ($fild->values as $val) {
                        $this->data[] = [$val->value, $contact->name, $email_list];
                        Contacts::updateOrCreate([
                            'account_id' => $contact->accountId,
                            'contact_id' => $contact->id,
                            'email' => $val->value,
                        ], [
                            'name' => $contact->name,
                            'delete' => 0,
                        ]);
                    }
                }
            }
        }
        $this->field_names = ['email', 'Name', 'email_list_ids'];
        return $this;
    }

    /**
     * @param $contact
     * @return $this
     */
    public function setContact(array $contact): self
    {
        $this->data = $contact;
        $this->field_names = ['email', 'delete', 'Name', 'email_list_ids'];
        return $this;
    }

    /**
     * @param string $name
     * @return int
     */
    public function getOrCreateList(string $name = 'Amo'): int
    {
        $uni = new UnisenderApi($this->Unisenderkey);
        $lists = json_decode($uni->getLists(), true)["result"];
        foreach ($lists as $list) {
            if ($list['title'] == $name) {
                return $list['id'];
            }
        }
        $list = $uni->createList([
            'api_key' => $this->Unisenderkey,
            'title' => $name
        ]);
        return json_decode($list, true)['result']['id'] ?? 0;
    }

    /**
     * @return bool
     */
    public function importContact(): bool
    {
        if (!empty($this->data)) {
            $uni = new UnisenderApi($this->Unisenderkey);
            $un = $uni->importContacts(
                array(
                    'api_key' => $this->Unisenderkey,
                    'field_names' => $this->field_names,
                    'data' => $this->data
                )
            );
            error_log($un . "\n", 3, dirname($_SERVER['DOCUMENT_ROOT']) . '/syslog.log');
            return true;
        }
        return true;
    }
}
