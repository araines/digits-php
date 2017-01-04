<?php

namespace Sportlobster\Digits;

class User
{
    private $id;
    private $phoneNumber;
    private $accessToken;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken(AccessToken $token)
    {
        $this->accessToken = $token;

        return $this;
    }
}
