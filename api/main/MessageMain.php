<?php

namespace main;

use model\{
    AdsModel, GameModel, UserModel
};
use service\Token;
use service\Redirect;

class MessageMain extends MainMain
{
    private $runtimeVariable;

    public function backCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'یک گزینه را انتخاب کنید...',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom($this->userType()),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    /**
     * @return string
     */
    private function userType(): string
    {
        $userType = $this->userModel()->findUserById($this->userId)->type;

        # check user exist in database
        if ($userType) {
            return $userType;
        } else {
            $this->userNotExist();
        }
    }

    /**
     * @return UserModel
     */
    private function userModel(): UserModel
    {
        return $this->container->get('userModel');
    }

    private function userNotExist()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'لطفا مجددا دکمه start را بزنید',
            'reply_markup' => [
                'keyboard' => $this->keyboard->userNotExistBottom(),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
        $this->io->sendResponse();
        exit;
    }

    public function showAliResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'Hi Ali',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom($this->userType()),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    /**
     * @throws \Exception
     */
    public function gamesCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'بازی مورد نظر خود را انتخاب کنید...',
            'reply_markup' => [
                'keyboard' => $this->keyboard->gameListBottom($this->runtimeVariable['allGames']),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function otherCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'دستور وارد شده صحیح نیست.',
        ];

        $this->io->setResponse($result);
    }

    public function errorCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'لطفا دوباره تلاش کنید',
        ];

        $this->io->setResponse($result);
    }

    /**
     * @throws \Exception
     */
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

    public function addUserToDb()
    {
        $this->userModel()->register(
            $this->userId,
            $this->firstName,
            $this->lastName,
            $this->telegramUsername,
            true
        );
    }

    public function startCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'خوش آمدید.',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom($this->userType()),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function getGameId()
    {
        $gameName = $this->text;
        $gameDocument = $this->gameModel()->findGameByName($gameName);
        if (!$gameDocument) {
            throw new \Exception('find game by name failed', 100);
        }
        $this->runtimeVariable['gameId'] = $gameDocument->_id;
    }

    public function showGameCreateResult()
    {
        $result = [
            'method' => 'sendGame',
            'chat_id' => $this->chatId,
            'game_short_name' => $this->runtimeVariable['gameId'],
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->showGameInline()
            ],
        ];

        $this->io->setResponse($result);
    }

    public function getAdsId()
    {
        # split text
        $splitText = explode("-", $this->text);

        # validate game id exist
        if (count($splitText) < 2) {
            throw new \Exception();
        }

        # set ads id
        $this->runtimeVariable['adsId'] = $splitText[1];
    }

    public function addAdsToDb()
    {
        $this->adsModel()->adsRegister($this->userId, $this->runtimeVariable['adsId']);
    }

    private function adsModel(): AdsModel
    {
        return $this->container->get('adsModel');
    }

    public function adsCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => 'خوش آمدید.',
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom($this->userType()),
                'resize_keyboard' => true
            ]
        ];

        $this->io->setResponse($result);
    }

    public function createUserLbId()
    {
        $this->runtimeVariable['userLbId'] = $this->userModel()->createUserLbId($this->userId);
    }

    public function createToken()
    {
        $this->token()->addClaim('uid', (string)$this->userId)
            ->addClaim('ulbid', $this->runtimeVariable['userLbId']);
        $this->runtimeVariable['token'] = $this->token()->create();
    }

    /**
     * @return Token
     */
    private function token(): Token
    {
        return $this->container->get('token');
    }

    public function generateRedirectUrl()
    {
        $urlNative = $this->setting['baseUrl']['hubView'] . '?token=' . $this->runtimeVariable['token'];
        $this->runtimeVariable['urlShortener'] = $this->redirect()->urlShorter($urlNative, 'h', $this->userId);
    }

    private function redirect(): Redirect
    {
        return $this->container->get('redirect');
    }

    public function showHubCreateResult()
    {
        $result = [
            'method' => 'sendPhoto',
            'chat_id' => $this->chatId,
            'photo' => $this->setting['cache']['hubPhoto'],
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->showHubInline($this->runtimeVariable['urlShortener'])
            ],
        ];

        $this->io->setResponse($result);
    }

    public function getCompetitionGame()
    {
        $gameDocument = $this->gameModel()->findCompetitionGame();
        if (!$gameDocument) {
            throw new \Exception();
        }
        $this->runtimeVariable['competitionGameId'] = $gameDocument->_id;
    }

    public function generateRedirectUrls()
    {
        # more info url
        $this->moreInfoUrl();

        # leaderboard url
        $this->lbUrl();
    }

    private function moreInfoUrl()
    {
        $moreInfoUrlNative = $this->setting['baseUrl']['hubView'] . '?token=' . $this->runtimeVariable['token'];
        $this->runtimeVariable['moreInfoUrlShortener'] = $this->redirect()->urlShorter(
            $moreInfoUrlNative,
            'm',
            $this->userId
        );
    }

    private function lbUrl()
    {
        $lbUrlNative = $this->setting['baseUrl']['hubView'] . 'leader-board?token=' . $this->runtimeVariable['token'];
        $this->runtimeVariable['lbUrlShortener'] = $this->redirect()->urlShorter($lbUrlNative, 'l', $this->userId);
    }

    public function competitionCreateResult()
    {
        $result = [
            'method' => 'sendGame',
            'chat_id' => $this->chatId,
            'game_short_name' => $this->runtimeVariable['competitionGameId'],
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->competitionInline(
                    $this->runtimeVariable['moreInfoUrlShortener'],
                    $this->runtimeVariable['lbUrlShortener']
                )
            ],
        ];

        $this->io->setResponse($result);
    }

    public function setGameId()
    {
        # split text
        $splitText = explode("-", $this->text);

        # validate game id exist
        if (count($splitText) < 2) {
            throw new \Exception();
        }

        # set game id
        $this->runtimeVariable['gameId'] = $splitText[1];
    }

    public function verifyGameExist()
    {
        $gameDocument = $this->gameModel()->findGameById($this->runtimeVariable['gameId']);
        if (!$gameDocument) {
            throw new \Exception();
        }
    }

    public function hubCreateResult()
    {
        $result = [
            'method' => 'sendGame',
            'chat_id' => $this->chatId,
            'game_short_name' => $this->runtimeVariable['gameId'],
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->showGameInline()
            ],
        ];

        $this->io->setResponse($result);
    }

    public function guideSetText()
    {
        $this->runtimeVariable['text'] = 'به دنیای بازی و هیجان خوش آمدید!
در  Wini Games شما می توانید بازی های دلخواه خود را انتخاب کنید و بدون نیاز به نصب، از آنها لذت ببرید. 
همچنین می توانید بازی ها را با دوستان خود و گروه های مختلف به اشتراک بگذارید و رقابتی هیجان انگیز را رقم بزنید. 
و مهم تر از همه اینکه شما می توانید با شرکت در مسابقات و لیگ های متنوع از جوایز نفیس و متنوع بهره مند شوید.
با Wini Games دنیایی جدید را تجربه خواهید کرد.';
    }

    public function guideCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => $this->runtimeVariable['text'],
        ];

        $this->io->setResponse($result);
    }

    public function aboutSetText()
    {
        $this->runtimeVariable['text'] = 'دوست خوش ذوق، مفتخریم که Wini Games را برای ساختن لحظات شاد و مهیج خود انتخاب کرده اید. Wini Games مجموعه ای است شامل بازی های جذاب که می توانید آنها را بدون نیاز به نصب در کنار خانواده و بهترین دوستان خود تجربه کنید. این بازی ها حاصل تلاش شبانه روزی کارشناسان Wini Games در گروه های طراحی، نرم افزار و فنی است.
مسابقات و لیگ های متنوع ما با جوایز نفیس و گرانبها را از دست ندهید. با ما همراه باشید!';
    }

    public function aboutCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => $this->runtimeVariable['text'],
        ];

        $this->io->setResponse($result);
    }

    public function playWithFriendSetText()
    {
        $this->runtimeVariable['text'] = '🔴 برای بازی با دوستان می توانید با انتخاب کلید زیر، فرد یا گروه مورد نظر را انتخاب کنید. همچنین می توانید در هر چت، عبارت را تایپ و بازی مورد نظر خود را انتخاب کنید.
💥رقابت و هیجان گروهی را از دست ندهید💥';
    }

    public function playWithFriendResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->request->message->chat->id,
            'text' => $this->runtimeVariable['text'],
            'reply_markup' => [
                'inline_keyboard' => $this->keyboard->showShareGameInline()
            ],
        ];

        $this->io->setResponse($result);
    }

    public function contactSetText()
    {
        $this->runtimeVariable['text'] = 'دوست عزیز، در صورت بروز هرگونه مشکل از طریق آی دی تلگرام زیر با ما در ارتباط باشید:
https://telegram.me/WiniSupport
مشتاقانه منتظر انتقادات و پیشنهادات شما هستیم.
شماره پشتیبانی:  88574979';
    }

    public function contactCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => $this->runtimeVariable['text'],
        ];

        $this->io->setResponse($result);
    }

    public function addContactToDb()
    {
        $this->userModel()->addContact($this->userId, $this->phoneNumber);
    }

    public function sendDeleteUser()
    {
        $result = file_get_contents("https://api.bot.net/hub/v1/user/sdu?phone_number=$this->phoneNumber&password=w!n!2096@");
        $result = json_decode($result, true);
        $this->log()->addInfo($result['code']);
        if ($result['code'] != 200) {
            throw new \Exception();
        }
    }

    public function loginCreateResult()
    {
        $result = [
            'method' => 'sendMessage',
            'chat_id' => $this->chatId,
            'text' => $this->runtimeVariable['text'],
            'reply_markup' => [
                'keyboard' => $this->keyboard->mainBottom('login')
            ],
        ];

        $this->io->setResponse($result);
    }

    public function addContactSetText()
    {
        $this->runtimeVariable['text'] = 'به دنیای بازی و هیجان خوش آمدید!
فرصت شرکت در مسابقه و رقابت هیجان انگیز ما رو  از دست نده. همین حالا شروع کن!';
    }
}
