<?php

namespace spec\Sportlobster\Digits\Exception;

use Sportlobster\Digits\Exception\KeyMismatchException;
use Sportlobster\Digits\Exception\DigitsException;
use PhpSpec\ObjectBehavior;

class KeyMismatchExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(KeyMismatchException::class);
        $this->shouldHaveType(\RuntimeException::class);
        $this->shouldHaveType(DigitsException::class);
    }
}
