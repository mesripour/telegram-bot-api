<?php

namespace main;

use League\Container\Container;
use Monolog\Logger;
use service\IO;

class MainMain
{
    public $container;
    /** @var IO $io */
    public $io;
    public $request;
    public $setting;
    /** @var $keyboard KeyboardMain */
    public $keyboard;

    public $chatId;
    public $userId;
    public $firstName;
    public $lastName;
    public $telegramUsername;
    public $text;
    public $phoneNumber;
    public $inlineMessageId;
    public $callbackQueryId;
    public $gameShortName;
    public $inlineQueryId;

    /**
     * MainMain constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setting = $container->get('settings');
        $this->io = $container->get('io');
        $this->request = $this->io->getRequest();
        $this->keyboard = $container->get('keyboard');
        $this->setRequestParameters();
    }

    /**
     * @return Logger
     */
    protected function log(): Logger
    {
        return $this->container->get('logger');
    }

    private function setRequestParameters()
    {
        $message = $this->request->message;
        $callBack = $this->request->callback_query;
        $inlineQuery = $this->request->inline_query;

        if ($message) {
            $this->chatId = $message->chat->id;
            $this->userId = $message->from->id;
            $this->firstName = $message->from->first_name;
            $this->lastName = $message->from->last_name;
            $this->telegramUsername = $message->from->username;
            $this->text = $message->text;
            $this->phoneNumber = $message->contact->phone_number ?? null;
        } elseif ($callBack) {
            $this->userId = $callBack->from->id;
            $this->inlineMessageId = $callBack->inline_message_id;
            $this->firstName = $callBack->from->first_name;
            $this->lastName = $callBack->from->last_name;
            $this->telegramUsername = $callBack->from->username;
            $this->callbackQueryId = $callBack->id;
            $this->gameShortName = $callBack->game_short_name;
        } elseif ($inlineQuery) {
            $this->inlineQueryId = $inlineQuery->id;
        }
    }
}
