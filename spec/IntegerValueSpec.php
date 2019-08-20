<?php

namespace spec\Purist\Struct;

use Purist\Struct\Constraint\Minimum;
use Purist\Struct\IntegerValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\ValidationFailed;

class IntegerValueSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Minimum(10));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(IntegerValue::class);
    }

    function it_will_not_validate_invalid_integers()
    {
        $this->validate('string')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate('1111hello')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(123.12)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate('123.12')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(true)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(null)->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_validate_integers()
    {
        $this->validate(123)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('456')->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(ValidationFailed::class)->duringGet('string');
        $this->shouldThrow(ValidationFailed::class)->duringGet('111hello');
        $this->shouldThrow(ValidationFailed::class)->duringGet(9);
        $this->shouldThrow(ValidationFailed::class)->duringGet(8);
    }

    function it_will_return_valid_value_as_integer()
    {
        $this->get(123)->shouldReturn(123);
        $this->get('456')->shouldReturn(456);
    }
}
