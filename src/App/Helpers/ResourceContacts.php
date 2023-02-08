<?php

namespace App\Helpers;

use App\Model\Contacts;

/**
 * Класс для создания добавления и обновление контактов
 */
class ResourceContacts
{
    /**
     * Метод добавление контакта
     * @param array $data
     * @param int $email_list
     * @return array|null
     */
    public function add(array $data, int $email_list): ?array
    {
        if ($this->hasEmail($data)) {
            $contact_id = $data['id'];
            $account_id = $data['account_id'];
            $name = $data['name'];
            $emails = $this->getEmail($data);
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    $contact = new Contacts();
                    $contact->account_id = $account_id;
                    $contact->contact_id = $contact_id;
                    $contact->name = $name;
                    $contact->email = $email;
                    $contact->delete = 0;
                    $contact->save();
                    $data_emails[] = [$email, 0, $name, $email_list];
                }
                return $data_emails;
            }
        }
        return null;
    }

    /**
     * Метод обновления контактов
     * @param $data
     * @param int $email_list
     * @return array|null
     */
    public function update($data, int $email_list): ?array
    {
        if ($this->hasEmail($data)) {
            $contact_id = $data['id'];
            $account_id = $data['account_id'];
            $name = $data['name'];
            $emails = $this->getEmail($data);
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    Contacts::updateOrCreate([
                        'account_id' => $account_id,
                        'contact_id' => $contact_id,
                        'email' => $email,
                    ], [
                        'name' => $name,
                        'delete' => 0,
                    ]);
                }
                $contacts = Contacts::where('contact_id', $contact_id)->get()->toArray();
                foreach ($contacts as $contact) {
                    if (!in_array($contact['email'], $emails)) {
                        Contacts::where('id', $contact['id'])->delete();
                        $contact['delete'] = 1;
                    }
                    $data_emails[] = [$contact['email'], $contact['delete'], $name, $email_list];
                }
                return $data_emails;
            }
        }
        return null;
    }

    /**
     * @param $data
     * @param int $email_list
     * @return array|null
     */
    public function delete($data, int $email_list): ?array
    {
        $contact_id = $data['id'];
        $contacts = Contacts::where('contact_id', $contact_id)->get()->toArray();
        foreach ($contacts as $contact) {
            $contact['delete'] = 1;
            $data_emails[] = [$contact['email'], $contact['delete'], $contact['name'], $email_list];
        }
        Contacts::where('contact_id', $contact_id)->delete();
        return $data_emails;
    }

    /**
     * @param $data
     * @return bool
     */
    public function hasEmail($data): bool
    {
        if (!empty($data["custom_fields"])) {
            foreach ($data["custom_fields"] as $value) {
                if ($value['name'] === "Email") {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $data
     * @return array
     */
    public function getEmail($data): array
    {
        $email = [];
        if (!empty($data["custom_fields"])) {
            foreach ($data["custom_fields"] as $value) {
                if ($value['name'] === "Email") {
                    foreach ($value['values'] as $v) {
                        $email[] = $v['value'];
                    }
                }
            }
        }
        return $email;
    }
}
