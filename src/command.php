<?php

# set telegram bot commands
$command = [
    'message' => [
        '/start' => 'start',
        'بازگشت' => 'back',
        '🎮 بازی ها' => 'games',
        'بازی ها' => 'games',
        'River Raid' => 'showGame',
        'Wood Cutter' => 'showGame',
        'Wave Surfer' => 'showGame',
        'Commando' => 'showGame',
        'Dodge' => 'showGame',
        'Castle' => 'showGame',
        'بازی با دوستان' => 'playWithFriend',
        '👫 بازی با دوستان' => 'playWithFriend',
        '🏠 ورود به دنیای' => 'showHub',
        'ورود به دنیای ' => 'showHub',
        'خانه' => 'showHub',
        '🏆 مسابقه و قرعه کشی' => 'competition',
        'مسابقه و قرعه کشی' => 'competition',
        '/start hub' => 'showHub',
        '📖 راهنما' => 'guide',
        'راهنما' => 'guide',
        '✏️ درباره Wini Games' => 'aboutUs',
        '☎️ تماس با ما' => 'contactUS',
        'تماس با ما' => 'contactUS',
        'deepLinkParameters' => [
            'game' => 'hubShowGame',
            'ads' => 'ads',
        ],
        'addContact' => 'addContact'
    ],
    'callback' => [
        'game' => 'playGame',
        'data' => [
            'subscribe' => 'subscribe',
        ]
    ],
    'inline' => [
        '' => 'gameList',
    ]
];
