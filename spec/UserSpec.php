<?php

namespace spec\Sportlobster\Digits;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sportlobster\Digits\AccessToken;
use Sportlobster\Digits\User;

class UserSpec extends ObjectBehavior
{
    public function let(AccessToken $accessToken)
    {
        $id = 'abc123';
        $phoneNumber = '+7791234567';

        $this->beConstructedWith($id, $phoneNumber, $accessToken);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    public function it_gets_id()
    {
        $this->getId()->shouldReturn('abc123');
    }

    public function it_gets_phone_number()
    {
        $this->getPhoneNumber()->shouldReturn('+7791234567');
    }

    public function it_gets_access_token(AccessToken $accessToken)
    {
        $id = 'abc123';
        $phoneNumber = '+7791234567';
        $this->beConstructedWith($id, $phoneNumber, $accessToken);

        $this->getAccessToken()->shouldReturn($accessToken);
    }
}
