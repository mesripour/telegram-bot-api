<?php

namespace controller;

use main\MessageMain;

class MessageController extends MainController
{
    public function start()
    {
        # 1.add user to db
        $this->messageMain()->addUserToDb();

        # 2.create and set result
        $this->messageMain()->startCreateResult();
    }

    private function messageMain(): MessageMain
    {
        return $this->container->get('messageMain');
    }

    public function back()
    {
        # create and set result
        $this->messageMain()->backCreateResult();
    }

    public function games()
    {
        # 1.get all games
        $this->messageMain()->getAllGames();

        # 2.create and set result
        $this->messageMain()->gamesCreateResult();
    }

    public function showGame()
    {
        # 1.find gameId from db by gameName
        $this->messageMain()->getGameId();

        # 2.create and set result
        $this->messageMain()->showGameCreateResult();
    }

    public function showHub()
    {
        # 1.create user leaderboard id
        $this->messageMain()->createUserLbId();

        # 2.create token
        $this->messageMain()->createToken();

        # 3.generate redirect url
        $this->messageMain()->generateRedirectUrl();

        # 4.create and set result
        $this->messageMain()->showHubCreateResult();
    }

    public function competition()
    {
        # 1.create user leaderboard id
        $this->messageMain()->createUserLbId();

        # 2.create token
        $this->messageMain()->createToken();

        # 3.find competition game from db
        $this->messageMain()->getCompetitionGame();

        # 4.generate redirect urls
        $this->messageMain()->generateRedirectUrls();

        # 5.create and set result
        $this->messageMain()->competitionCreateResult();
    }

    public function hubShowGame()
    {
        # 1.set game id from text
        $this->messageMain()->setGameId();

        # 2.verify game is exist
        $this->messageMain()->verifyGameExist();

        # 3.add user to db
        $this->messageMain()->addUserToDb();

        # 4.create and set result
        $this->messageMain()->hubCreateResult();
    }

    public function ads()
    {
        # 1.get ads id
        $this->messageMain()->getAdsId();

        # 2.add user to db
        $this->messageMain()->addUserToDb();

        # 3.add ads to db
        $this->messageMain()->addAdsToDb();

        # 4.create and set result
        $this->messageMain()->adsCreateResult();
    }

    public function messageOther()
    {
        # create and set result
        $this->messageMain()->otherCreateResult();
    }

    public function error()
    {
        # create and set result
        $this->messageMain()->errorCreateResult();
    }

    public function guide()
    {
        # 1.set guide text
        $this->messageMain()->guideSetText();

        # 2.create and set result
        $this->messageMain()->guideCreateResult();
    }

    public function aboutUs()
    {
        # 1.set about us text
        $this->messageMain()->aboutSetText();

        # 2.create and set result
        $this->messageMain()->aboutCreateResult();
    }

    public function PlayWithFriend()
    {
        # 1.set text
        $this->messageMain()->playWithFriendSetText();

        # 2.create and set result
        $this->messageMain()->playWithFriendResult();
    }

    public function contactUS()
    {
        # 1.set contact us text
        $this->messageMain()->contactSetText();

        # 2.create and set result
        $this->messageMain()->contactCreateResult();
    }

    public function addContact()
    {

        # 1.check Phone Number
        $this->messageMain()->sendDeleteUser();

        # 2add contact to db
        $this->messageMain()->addContactToDb();

        # 3.set add contact text
        $this->messageMain()->addContactSetText();

        # 4.create and set result
        $this->messageMain()->loginCreateResult();
    }
}
