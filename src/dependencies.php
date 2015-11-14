<?php

# get container
$container = new League\Container\Container;

/** @var $setting array */
$container->share('settings', $setting);

$container->share('mongo', (new MongoDB\Client())->selectDatabase($setting['mongodb']['dbname']));

# input output handler
$container->share('io', new \service\IO());

$container->share('logger', function () use ($setting) {
    $log = new \Monolog\Logger($setting['logger']['name']);
    $log->pushHandler(new \Monolog\Handler\StreamHandler($setting['logger']['path'], \Monolog\Logger::DEBUG));
    return $log;
});

$container->share('keyboard', new \main\KeyboardMain());

$container->share('userModel', new \model\UserModel($container));

$container->share('token', new \service\Token($container));

$container->share('redirectModel', new \model\RedirectModel($container));

$container->share('redirect', new \service\Redirect($container));

$container->share('callbackMain', new \main\CallbackMain($container));

$container->share('inlineMain', new \main\InlineMain($container));

$container->share('messageMain', new \main\MessageMain($container));

$container->share('gameModel', new \model\GameModel($container));

$container->share('adsModel', new \model\AdsModel($container));
