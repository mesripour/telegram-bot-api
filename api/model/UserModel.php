<?php

namespace model;

class UserModel extends MainModel
{
    /**
     * @param string $userId
     * @param string $firstName
     * @param null | string $lastName
     * @param null | string $telegramUsername
     * @param bool $botInitiate
     */
    public function register(string $userId, string $firstName, $lastName, $telegramUsername, $botInitiate = false)
    {
        $userDocument = $this->findUserById($userId);

        if (!$userDocument) {
            $username = $this->generateUniqueUsername($firstName);
            $this->mongo('user')->insertOne([
                '_id' => $userId,
                'profile' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName ?? '',
                    'username' => $username,
                    'telegram_username' => $telegramUsername ?? '',
                    'bot_initiate' => $botInitiate,
                ],
                'type' => 'register',
                'create_time' => time()
            ]);
        }
    }

    /**
     * @param string $userId
     * @return array|null|object
     */
    public function findUserById(string $userId)
    {
        return $this->mongo('user')->findOne(['_id' => $userId]);
    }

    /**
     * @param string $firstName
     * @return string
     */
    private function generateUniqueUsername(string $firstName): string
    {
        # max username is 10 character
        if (strlen($firstName) > 10) {
            $firstName = substr($firstName, 0, 10);
        }

        $flag = true;
        $username = $firstName;
        while ($flag) {
            $userDocument = $this->findByUsername($username);
            if (is_null($userDocument)) {
                $flag = false;
            }
        }
        return $username;
    }

    /**
     * @param string $userName
     * @return array|null|object
     */
    public function findByUsername(string $userName)
    {
        return $this->mongo('user')->findOne(['profile.username' => $userName]);
    }

    /**
     * @param string $userId
     * @param string $phoneNumber
     */
    public function addContact(string $userId, string $phoneNumber)
    {
        $provider = $this->getProvider($phoneNumber);
        $this->mongo('user')->updateOne(
            ['_id' => $userId],
            [
                '$set' => [
                    'profile.phone_number.telegram' => $phoneNumber,
                    'profile.phone_number.provider' => $provider,
                    'type' => 'login'
                ],
            ]
        );
    }

    /**
     * @param string $userId
     * @return string
     * @throws \Exception
     */
    public function createUserLbId(string $userId): string
    {
        $userDocument = $this->findUserById($userId);
        if (!$userDocument) {
            throw new \Exception();
        }
        $userProfile = $userDocument->profile;
        $userState = $userProfile->state ?? '0';
        $userLbId = $userProfile->username . '.' . $userState . '.' . $userId;
        return $userLbId;
    }

    /**
     * @param string $userId
     */
    public function subscribeUser(string $userId)
    {
        $this->mongo('user')->updateOne(
            ['_id' => $userId],
            [
                '$set' => [
                    'type' => 'subscribe'
                ]
            ]
        );
    }
}
