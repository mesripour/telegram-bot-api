<?php

namespace controller;

use main\InlineMain;

class InlineController extends MainController
{
    public function gameList()
    {
        # 1.get all games from database
        $this->inlineMain()->getAllGames();

        # 2.prepare telegram games format
        $this->inlineMain()->createTelegramFormatGames();

        # 3.create and set result
        $this->inlineMain()->listCreateResult();
    }

    private function inlineMain(): InlineMain
    {
        return $this->container->get('inlineMain');
    }
}
