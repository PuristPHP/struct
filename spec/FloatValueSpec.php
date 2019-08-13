<?php

namespace spec\Purist\Struct;

use Purist\Struct\FloatValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FloatValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FloatValue::class);
    }

    function it_will_not_validate_invalid_floats()
    {
        $this->validate('string')->shouldReturn(false);
        $this->validate('11.23hello')->shouldReturn(false);
        $this->validate(null)->shouldReturn(false);
        $this->validate(true)->shouldReturn(false);
    }

    function it_will_validate_floats()
    {
        $this->validate(123)->shouldReturn(true);
        $this->validate(123.123)->shouldReturn(true);
        $this->validate('123.123')->shouldReturn(true);
        $this->validate('123')->shouldReturn(true);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet('string');
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet('11.23hello');
    }

    function it_will_return_valid_value_as_float()
    {
        $this->get(123)->shouldReturn(123.0);
        $this->get('456')->shouldReturn(456.0);
        $this->get('456.789')->shouldReturn(456.789);
    }
}
