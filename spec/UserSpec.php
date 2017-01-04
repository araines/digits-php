<?php

namespace spec\Sportlobster\Digits;

use Sportlobster\Digits\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }
}
