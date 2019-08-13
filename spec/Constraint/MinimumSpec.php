<?php

namespace spec\Purist\Struct\Constraint;

use Purist\Struct\Constraint\Constraint;
use Purist\Struct\Constraint\Minimum;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MinimumSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(50);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Minimum::class);
        $this->shouldImplement(Constraint::class);
    }

    function it_will_pass_numbers_equal_or_over_set_value()
    {
        $this->validate(50)->shouldReturn(true);
        $this->validate(51)->shouldReturn(true);
        $this->validate(100)->shouldReturn(true);
    }

    function it_will_fail_numbers_under_set_value()
    {
        $this->validate(49)->shouldReturn(false);
        $this->validate(0)->shouldReturn(false);
        $this->validate(-1)->shouldReturn(false);
    }
}
