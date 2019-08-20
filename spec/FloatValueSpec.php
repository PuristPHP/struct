<?php

namespace spec\Purist\Struct;

use Purist\Struct\FloatValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\ValidationFailed;

class FloatValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FloatValue::class);
    }

    function it_will_not_validate_invalid_floats()
    {
        $this->validate('string')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate('11.23hello')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(null)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(true)->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_validate_floats()
    {
        $this->validate(123)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(123.123)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('123.123')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('123')->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(ValidationFailed::class)->duringGet('string');
        $this->shouldThrow(ValidationFailed::class)->duringGet('11.23hello');
    }

    function it_will_return_valid_value_as_float()
    {
        $this->get(123)->shouldReturn(123.0);
        $this->get('456')->shouldReturn(456.0);
        $this->get('456.789')->shouldReturn(456.789);
    }
}
