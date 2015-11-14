<?php

namespace model;

class GameModel extends MainModel
{
    /**
     * @param string $gameId
     * @return array|null|object
     */
    public function findGameById(string $gameId)
    {
        return $this->mongo('game')->findOne(['_id' => $gameId]);
    }

    /**
     * @param string $gameName
     * @return array|null|object
     */
    public function findGameByName(string $gameName)
    {
        return $this->mongo('game')->findOne(['name' => $gameName]);
    }

    /**
     * @return array
     */
    public function findAllGame()
    {
        return $this->mongo('game')->find([],['sort'=>["competition.activate"=>-1]])->toArray();
    }

    /**
     * @return array|null|object
     */
    public function findCompetitionGame()
    {
        return $this->mongo('game')->findOne(['competition.activate' => true]);
    }
}
