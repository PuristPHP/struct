<?php

namespace spec\Purist\Struct\Values;

use Purist\Struct\Values\IntegerValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IntegerValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IntegerValue::class);
    }

    function it_will_not_validate_invalid_integers()
    {
        $this->validate('string')->shouldReturn(false);
        $this->validate('1111hello')->shouldReturn(false);
        $this->validate(123.12)->shouldReturn(false);
        $this->validate('123.12')->shouldReturn(false);
        $this->validate(true)->shouldReturn(false);
        $this->validate(null)->shouldReturn(false);
    }

    function it_will_validate_integers()
    {
        $this->validate(123)->shouldReturn(true);
        $this->validate('456')->shouldReturn(true);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet('string');
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet('111hello');
    }

    function it_will_return_valid_value_as_integer()
    {
        $this->get(123)->shouldReturn(123);
        $this->get('456')->shouldReturn(456);
    }
}
