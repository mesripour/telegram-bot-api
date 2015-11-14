<?php

namespace controller;

use League\Container\Container;

class MainController
{
    public $container;

    /**
     * MainController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}