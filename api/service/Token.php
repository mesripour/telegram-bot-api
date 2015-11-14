<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-10
 * Time: 3:14 PM
 */

namespace service;

use Lcobucci\JWT\{
    Builder, Parser, Signer\Hmac\Sha256
};
use League\Container\Container;
use Monolog\Logger;


class Token
{
    const PRIVATE_KEY = '<privatekey>';
    const ONE_MONTH = 2592000;

    private $container;
    private $token;
    private $claimNames;
    private $claimValues;

    /**
     * Token constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->addClaim('exp', time() + self::ONE_MONTH);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addClaim($name, $value)
    {
        $this->claimNames[] = $name;
        $this->claimValues[] = $value;

        return $this;
    }

    public function create()
    {
        $signer = new Sha256();
        $token = (new Builder())
            ->set('names', $this->claimNames)
            ->set('values', $this->claimValues)
            ->sign($signer, self::PRIVATE_KEY)
            ->getToken();

        return $this->token = (string)$token;
    }

    public function validate()
    {
        $signer = new Sha256();
        $parsedToken = (new Parser())->parse((string)$this->token);
        if (!$parsedToken->verify($signer, self::PRIVATE_KEY)) {
            throw new \Exception();
        }

        $this->claimNames = $parsedToken->getClaim('names');
        $this->claimValues = $parsedToken->getClaim('values');
    }

    /**
     * @param $name
     * @return $this
     */
    public function removeClaim($name)
    {
        $key = array_search($name, $this->claimNames);

        if ($key) {
            unset($this->claimNames[$key]);
            $this->claimNames = array_values($this->claimNames);

            unset($this->claimValues[$key]);
            $this->claimValues = array_values($this->claimValues);
        }

        return $this;
    }

    public function updateClaim($name, $value)
    {
        $key = array_search($name, $this->claimNames);

        if ($key !== false) {
            $this->claimValues[$key] = $value;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClaims()
    {
        return $this->pureClaims();
    }

    /**
     * @return mixed
     */
    private function pureClaims()
    {
        $names = $this->claimNames;
        $values = $this->claimValues;

        for ($i = 0; $i < count($names); $i++) {
            $cleanClaims[$names[$i]] = $values[$i];
        }

        return $cleanClaims ?? [];
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return Logger
     */
    private function log(): Logger
    {
        return $this->container->get('logger');
    }
}