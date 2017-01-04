<?php

namespace spec\Sportlobster\Digits\Exception;

use Sportlobster\Digits\Exception\InvalidHostException;
use Sportlobster\Digits\Exception\DigitsException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InvalidHostExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(InvalidHostException::class);
        $this->shouldHaveType(\RuntimeException::class);
        $this->shouldHaveType(DigitsException::class);
    }
}
