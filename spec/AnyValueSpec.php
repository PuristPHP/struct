<?php

namespace spec\Purist\Struct;

use Purist\Struct\AnyValue;
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
        $this->validate('anything')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(123)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(true)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(new \stdClass())->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate([])->callOnWrappedObject('hasErrors')->shouldReturn(false);
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
