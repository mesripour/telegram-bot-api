<?php

namespace main;

use model\GameModel;

class InlineMain extends MainMain
{
    private $runtimeVariable;

    public function getAllGames()
    {
        $this->runtimeVariable['allGames'] = $this->gameModel()->findAllGame();
        if (!$this->runtimeVariable['allGames']) {
            throw new \Exception();
        }
    }

    /**
     * @return GameModel
     */
    private function gameModel(): GameModel
    {
        return $this->container->get('gameModel');
    }

    public function createTelegramFormatGames()
    {
        foreach ($this->runtimeVariable['allGames'] as $key => $value) {
            $gameId = $value->_id;
            $game[$key] = [
                'type' => 'game',
                'id' => $gameId . 'id',
                'game_short_name' => $gameId,
                'reply_markup' => [
                    'inline_keyboard' => $this->keyboard->gameListInline($this->setting)
                ],
            ];
        }

        $this->runtimeVariable['telegramGamesFormat'] = $game;
    }

    public function listCreateResult()
    {
        $result = [
            'method' => 'answerInlineQuery',
            'inline_query_id' => $this->inlineQueryId,
            'results' => $this->runtimeVariable['telegramGamesFormat']
        ];

        $this->io->setResponse($result);
    }
}