<?php

namespace model;

use League\Container\Container;
use MongoDB\Collection;

class MainModel
{
    public $container;

    /**
     * MainModel constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $collection
     * @return Collection
     */
    public function mongo(string $collection): Collection
    {
        return $this->container->get('mongo')->{$collection};
    }
}
