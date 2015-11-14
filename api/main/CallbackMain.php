<?php

namespace main;

use model\{
    GameModel, UserModel
};
use service\Token;

class CallbackMain extends MainMain
{
    private $runtimeVariable;

    /**
     * @return UserModel
     */
    private function userModel(): UserModel
    {
        return $this->container->get('userModel');
    }

    /**
     * @return GameModel
     */
    private function gameModel(): GameModel
    {
        return $this->container->get('gameModel');
    }

    /**
     * @return Token
     */
    private function token(): Token
    {
        return $this->container->get('token');
    }

    public function addUserToDb()
    {
        $this->userModel()->register($this->userId, $this->firstName, $this->lastName, $this->telegramUsername);
    }

    public function getUrlFromDb()
    {
        $gameDocument = $this->gameModel()->findGameById($this->gameShortName);
        if (!$gameDocument) {
            throw new \Exception();
        }
        $this->runtimeVariable['url'] = $gameDocument->url;
    }

    public function createToken()
    {
        $userLbId = $this->userModel()->createUserLbId($this->userId);
        $this->token()->addClaim('uid', (string)$this->userId)
            ->addClaim('ulbid', $userLbId);
        $this->runtimeVariable['token'] = $this->token()->create();
    }

    public function playCreateResult()
    {
        $result = [
            'method' => 'answerCallbackQuery',
            'callback_query_id' => $this->callbackQueryId,
            'url' => $this->runtimeVariable['url'] . "?token=" . $this->runtimeVariable['token'] . "&game_id=$this->gameShortName&imi=$this->inlineMessageId&"
        ];

        $this->io->setResponse($result);
    }

    public function getUserType()
    {
        $this->runtimeVariable['userType'] = $this->userModel()->findUserById($this->userId)->type;
    }

    public function subSetText()
    {
        if ($this->runtimeVariable['userType'] == 'login') {
            $this->runtimeVariable['text'] = 'شما قبلا ثبت نام کرده اید';
        } else {
            $this->runtimeVariable['text'] = 'لطفا جهت شرکت در مسابقه از طریق منو به صورت رایگان ثبت نام کنید';
        }
    }

    public function subCreateResult()
    {
        $result = [
            'method' => 'answerCallbackQuery',
            'callback_query_id' => $this->callbackQueryId,
            'text' => $this->runtimeVariable['text'],
            'show_alert' => true,
        ];

        $this->io->setResponse($result);
    }
}
