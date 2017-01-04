<?php

namespace Sportlobster\Digits;

/**
 * A Digits User object.
 */
class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var AccessToken
     */
    private $accessToken;

    public function __construct($idStr, $phoneNumber, AccessToken $accessToken)
    {
        $this->id = $idStr;
        $this->phoneNumber = $phoneNumber;
        $this->accessToken = $accessToken;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
