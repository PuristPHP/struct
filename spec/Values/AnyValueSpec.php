<?php

namespace spec\Purist\Struct\Values;

use Purist\Struct\Values\AnyValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AnyValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AnyValue::class);
    }

    function it_will_always_validate_as_true()
    {
        $this->validate('anything')->shouldReturn(true);
        $this->validate(123)->shouldReturn(true);
        $this->validate(true)->shouldReturn(true);
        $this->validate(new \stdClass())->shouldReturn(true);
        $this->validate([])->shouldReturn(true);
    }

    function it_will_always_return_passed_value()
    {
        $this->get('anything')->shouldReturn('anything');
        $this->get(123)->shouldReturn(123);
        $this->get(true)->shouldReturn(true);
        $this->get($value = new \stdClass())->shouldReturn($value);
        $this->get([])->shouldReturn([]);
    }
}
