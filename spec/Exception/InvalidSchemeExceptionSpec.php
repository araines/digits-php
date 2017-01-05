<?php

namespace spec\Sportlobster\Digits\Exception;

use Sportlobster\Digits\Exception\InvalidSchemeException;
use Sportlobster\Digits\Exception\DigitsException;
use PhpSpec\ObjectBehavior;

class InvalidSchemeExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(InvalidSchemeException::class);
        $this->shouldHaveType(\RuntimeException::class);
        $this->shouldHaveType(DigitsException::class);
    }
}
