<?php

namespace spec\Sportlobster\Digits\Exception;

use Sportlobster\Digits\Exception\AuthenticationException;
use Sportlobster\Digits\Exception\DigitsException;
use PhpSpec\ObjectBehavior;

class AuthenticationExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticationException::class);
        $this->shouldHaveType(\RuntimeException::class);
        $this->shouldHaveType(DigitsException::class);
    }
}
