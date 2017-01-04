<?php

namespace Sportlobster\Digits;

/**
 * A Digits AccessToken object
 */
class AccessToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $secret;

    public function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getSecret()
    {
        return $this->secret;
    }
}
