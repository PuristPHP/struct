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
        $this->validate('string')->shouldReturn(false);
        $this->validate(123)->shouldReturn(false);
        $this->validate(new \stdClass())->shouldReturn(false);
        $this->validate([])->shouldReturn(false);
        $this->validate(123.123)->shouldReturn(false);
        $this->validate(null)->shouldReturn(false);
    }

    function it_will_validate_booleans()
    {
        $this->validate(true)->shouldReturn(true);
        $this->validate('true')->shouldReturn(true);
        $this->validate('on')->shouldReturn(true);
        $this->validate('1')->shouldReturn(true);
        $this->validate(1)->shouldReturn(true);
        $this->validate(false)->shouldReturn(true);
        $this->validate('false')->shouldReturn(true);
        $this->validate('off')->shouldReturn(true);
        $this->validate('0')->shouldReturn(true);
        $this->validate(0)->shouldReturn(true);
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
