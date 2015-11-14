<?php
/**
 * Created by PhpStorm.
 * User: h.soltani
 * Date: 2/25/2017
 * Time: 12:30 PM
 */

namespace service;

use League\Container\Container;
use model\RedirectModel;

class Redirect
{
    private $container;
    private $setting;

    /**
     * UrlShorter constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setting = $container->get('settings');
    }

    /**
     * @return RedirectModel
     */
    private function urlShorterModel(): RedirectModel
    {
        return $this->container->get('redirectModel');
    }

    /**
     * @param string $url
     * @param string $type
     * @param string $userId
     * @return string
     */
    public function urlShorter(string $url, string $type, string $userId)
    {
        $this->urlShorterModel()->upsertUrl($url, $type, $userId);

        $baseRedirectUrl = $this->setting['baseUrl']['redirect'];

        return $baseRedirectUrl . $userId . "/$type";
    }
}
