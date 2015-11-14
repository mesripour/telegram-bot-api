<?php

namespace controller;

use main\CallbackMain;

class CallbackController extends MainController
{
    public function playGame()
    {
        # 1.add user to db
        $this->callbackMain()->addUserToDb();

        # 2.get game url from db
        $this->callbackMain()->getUrlFromDb();

        # 3.create token
        $this->callbackMain()->createToken();

        # 4.create and set result
        $this->callbackMain()->playCreateResult();
    }

    private function callbackMain(): CallbackMain
    {
        return $this->container->get('callbackMain');
    }

    public function subscribe()
    {
        # 1.get user type from db
        $this->callbackMain()->getUserType();

        # 2.subscribe set text
        $this->callbackMain()->subSetText();

        # 3.create and set result
        $this->callbackMain()->subCreateResult();
    }
}
