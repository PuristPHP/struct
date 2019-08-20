<?php

namespace spec\Purist\Struct;

use Purist\Struct\BooleanValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\ValidationFailed;

class BooleanValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BooleanValue::class);
    }

    function it_will_not_validate_invalid_booleans()
    {
        $this->validate('string')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(123)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(new \stdClass())->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate([])->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(123.123)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(null)->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_validate_booleans()
    {
        $this->validate(true)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('true')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('on')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('1')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(1)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(false)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('false')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('off')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('0')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(0)->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(ValidationFailed::class)->duringGet('string');
        $this->shouldThrow(ValidationFailed::class)->duringGet(123);
    }

    function it_will_return_valid_value_as_boolean()
    {
        $this->get('on')->shouldReturn(true);
        $this->get('0')->shouldReturn(false);
    }
}
