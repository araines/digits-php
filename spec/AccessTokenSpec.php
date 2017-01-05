<?php

namespace spec\Sportlobster\Digits;

use Sportlobster\Digits\AccessToken;
use PhpSpec\ObjectBehavior;

class AccessTokenSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('tokenValue', 'secretValue');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AccessToken::class);
    }

    public function it_gets_token()
    {
        $this->getToken()->shouldReturn('tokenValue');
    }

    public function it_gets_secret()
    {
        $this->getSecret()->shouldReturn('secretValue');
    }
}
