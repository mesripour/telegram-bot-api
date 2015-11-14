<?php

namespace model;

class AdsModel extends MainModel
{
    /**
     * @param string $userId
     * @param int $adsId
     */
    public function adsRegister(string $userId, int $adsId)
    {
        $adsDocument = $this->findAdsByUserId($userId);

        if (!$adsDocument) {
            $this->mongo('ads')->insertOne([
                '_id' => $userId,
                'ads_id' => $adsId,
                'create_time' => time()
            ]);
        }
    }

    /**
     * @param string $userId
     * @return array|null|object
     */
    public function findAdsByUserId(string $userId)
    {
        return $this->mongo('ads')->findOne(['_id' => $userId]);
    }
}
