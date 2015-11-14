<?php

namespace main;

class KeyboardMain
{
    /**
     * @param string $userType
     * @return array
     */
    public function mainBottom(string $userType): array
    {
        switch ($userType) {
            case 'register':
                $keyboard = $this->guestMainBottom();
                break;
            case 'login':
                $keyboard = $this->loginMainBottom();
                break;
            case 'subscribe':
                $keyboard = $this->subMainBottom();
                break;
            default:
                $keyboard = 'register';
        }

        return $keyboard;
    }

    /**
     * @return array
     */
    private function guestMainBottom():array
    {
        $keyboard = [
            [
                ['text' => '👫 بازی با دوستان']
            ],
            [
                ['text' => 'ثبت نام رایگان', 'request_contact' => true]
            ],
            [
                ['text' => '🏠 ورود به دنیای ']
            ],
            [
                ['text' => '🎮 بازی ها']
            ],
            [
                ['text' => '🏆 مسابقه و قرعه کشی']
            ],
            [
                ['text' => '📖 راهنما']
            ],
            [
                ['text' => '✏️ درباره Wini Games']
            ],
            [
                ['text' => '☎️ تماس با ما']
            ],
        ];
        return $keyboard;
    }

    /**
     * @return array
     */
    private function loginMainBottom():array
    {
        $keyboard = [
            [
                ['text' => '👫 بازی با دوستان']
            ],
            [
                ['text' => '🏠 ورود به دنیای ']
            ],
            [
                ['text' => '🎮 بازی ها']
            ],
            [
                ['text' => '🏆 مسابقه و قرعه کشی']
            ],
            [
                ['text' => '📖 راهنما']
            ],
            [
                ['text' => '✏️ درباره Wini Games']
            ],
            [
                ['text' => '☎️ تماس با ما']
            ],
        ];
        return $keyboard;
    }

    /**
     * @return array
     */
    private function subMainBottom():array
    {
        $keyboard = [
            [
                ['text' => '👫 بازی با دوستان']
            ],
            [
                ['text' => '🏠 ورود به دنیای ']
            ],
            [
                ['text' => '🎮 بازی ها']
            ],
            [
                ['text' => '🏆 مسابقه و قرعه کشی']
            ],
            [
                ['text' => '📖 راهنما']
            ],
            [
                ['text' => '✏️ درباره Wini Games']
            ],
            [
                ['text' => '☎️ تماس با ما']
            ],
        ];
        return $keyboard;
    }

    /**
     * @param array $games
     * @return array
     */
    public function gameListBottom(array $games): array
    {
        foreach ($games as $key => $value) {
                $keyboard[][] = ['text' => $value['name']];
        }

        $keyboard[][] = ['text' => 'بازگشت'];

        return $keyboard;
    }

    /**
     * @param string $shortenerUrl
     * @return array
     */
    public function showHubInline(string $shortenerUrl):array
    {
        $keyboard = [
            [
                ['text' => 'ورود به دنیای ', 'url' => $shortenerUrl]
            ]
        ];

        return $keyboard;
    }
    /**
     * @param string $moreInfoUrl
     * @param string $lbUrl
     * @return array
     */
    public function competitionInline(string $moreInfoUrl, string $lbUrl): array
    {
        $keyboard = [
            [
                ['text' => 'شروع بازی', 'callback_game' => 'play'],
                ['text' => 'بازی با دوستان', 'switch_inline_query' => ''],
            ],
            [
                [
                    'text' => 'نفرات برتر',
                    'url' => $lbUrl
                ],
                ['text' => 'ثبت نام در مسابقه', 'callback_data' => 'subscribe'],

            ]
        ];

        return $keyboard;
    }

    /**
     * @return array
     */
    public function showGameInline(): array
    {
        $keyboard = [
            [
                ['text' => 'شروع بازی', 'callback_game' => 'play'],
                ['text' => 'بازی با دوستان', 'switch_inline_query' => ''],
            ],
        ];

        return $keyboard;
    }

    /**
     * @return array
     */
    public function showShareGameInline(): array
    {
        $keyboard = [
            [
                ['text' => 'بازی با دوستان', 'switch_inline_query' => ''],
            ],
        ];

        return $keyboard;
    }

     /**
     * @param array $setting
     * @return array
     */
    public function gameListInline(array $setting): array
    {
        $keyboard = [
            [
                ['text' => 'شروع بازی', 'callback_game' => 'play'],
                ['text' => 'بازی با دوست', 'switch_inline_query' => ''],
            ],
            [
                ['text' => 'ورود به بات ', 'url' => 'telegram.me/' . $setting['bot']['name']],
            ],
        ];

        return $keyboard;
    }

    /**
     * @return array
     */
    public function userNotExistBottom():array
    {
        $keyboard = [
            [
                ['text' => '/start']
            ],
        ];
        return $keyboard;
    }
}
