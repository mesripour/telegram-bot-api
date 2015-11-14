<?php

use League\Container\Container;

# get io object
/** @var service\IO $io */
$io = $container->get('io');
$request = $io->getRequest();

# log
/** @var \Monolog\Logger $log */
$log = $container->get('logger');
$log->addInfo(json_encode((array)$request));

# load commands
require 'command.php';
/** @var array $command */

# define input type
$dispatch = new stdClass();
switch ($request) {
    case isset($request->message):
        $dispatch = message($request, $dispatch, $container, $command);
        break;
    case isset($request->callback_query):
        $dispatch = callback($request, $dispatch, $container, $command);
        break;
    case isset($request->inline_query):
        $dispatch = inline($request, $dispatch, $container, $command);
        break;
    default:
        $dispatch = other($dispatch);
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $command
 * @return stdClass
 */
function message(stdClass $request, stdClass $dispatch, Container $container, array $command): stdClass
{
    if ($request->message->text) { # for send user text
        $method = messageText($request->message->text, $command);
    } elseif ($request->message->contact) { # for send user contact
        $method = $command['message']['addContact'];
    }

    $dispatch->controller = 'MessageController';
    $dispatch->method = $method;

    # method not found
    if (!$dispatch->method) {
        $dispatch = other($dispatch);
    }

    return $dispatch;
}

/**
 * @param string $text
 * @param array $command
 * @return string | null
 */
function messageText(string $text, array $command)
{
    # find from message dictionary
    $message = $command['message'];
    $method = $message[$text];

    # find from multiple deep link
    if (!$method) {
        $method = multipleDeepLink($text, $command['message']['deepLinkParameters']);
    }

    return $method;
}

/**
 * @param string $text
 * @param array $parameters
 * @return mixed
 */
function multipleDeepLink(string $text, array $parameters)
{
    # get parameter (for example: /start game-river ---result---> game)
    $splitText = explode("-", $text);
    $parameter = substr($splitText[0], 7);

    # find from deep link parameters dictionary
    $method = $parameters[$parameter] ?? null;

    return $method;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $command
 * @return stdClass
 */
function callback(stdClass $request, stdClass $dispatch, Container $container, array $command): stdClass
{
    # set controller name
    $dispatch->controller = 'CallbackController';

    # set method name
    if ($request->callback_query->game_short_name) {
        $dispatch->method = $command['callback']['game'];
    } elseif ($request->callback_query->data) {
        $callbackQueryData = $request->callback_query->data;
        $callbackData = $command['callback']['data'];
        $dispatch->method = $callbackData[$callbackQueryData];
    }

    # method not found
    if (!$dispatch->method) {
        $dispatch = other($dispatch);
    }

    return $dispatch;
}

/**
 * @param stdClass $request
 * @param stdClass $dispatch
 * @param Container $container
 * @param array $command
 * @return stdClass
 */
function inline(stdClass $request, stdClass $dispatch, Container $container, array $command): stdClass
{
    $query = $request->inline_query->query;
    $inline = $command['inline'];

    # set controller and method name
    $dispatch->controller = 'InlineController';
    $dispatch->method = $inline[$query];

    # method not found
    if (!$dispatch->method) {
        $dispatch = other($dispatch);
    }

    return $dispatch;
}

function other(stdClass $dispatch): stdClass
{
    $dispatch->controller = 'MessageController';
    $dispatch->method = 'messageOther';
    return $dispatch;
}
