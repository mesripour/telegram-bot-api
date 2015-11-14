<?php

namespace model;

class RedirectModel extends MainModel
{
    /**
     * @param string $url
     * @param string $type
     * @param string $userId
     */
    public function upsertUrl(string $url, string $type, string $userId)
    {
        $this->mongo('redirect')->updateOne(
            ['_id' => $userId],
            [
                '$set' => [
                    "$type.url" => $url,
                    "$type.last_access_time" => time()
                ]
            ],
            ['upsert' => true]
        );
    }
}
