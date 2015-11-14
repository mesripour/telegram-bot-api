<?php

# get controller and method name
$controllerName = $dispatch->controller;
$methodName = $dispatch->method;
$namespace = '\controller\\' . $controllerName;
$controller = new $namespace($container);

# execute method
try {
    $controller->$methodName();
} catch (Exception $e) {
    /** @var \main\MessageMain $messageMain */
    $messageMain = $container->get('messageMain');
    $messageMain->errorCreateResult();
}

# send response
/** @var \service\IO $io */
$io = $container->get('io');
$io->sendResponse();
