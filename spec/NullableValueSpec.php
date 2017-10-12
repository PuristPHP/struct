<?php

namespace spec\Purist\Struct;

use Purist\Struct\NullableValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\Value;

class NullableValueSpec extends ObjectBehavior
{
    function let(Value $value)
    {
        $this->beConstructedWith($value);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NullableValue::class);
    }

    function it_will_validate_as_true_if_null_or_if_value_validates(Value $value)
    {
        $this->validate(null)->shouldReturn(true);
        $this->validate('')->shouldReturn(true);

        $value->validate('string')->willReturn(true);
        $this->validate('string')->shouldReturn(true);

        $value->validate(true)->willReturn(false);
        $this->validate(true)->shouldReturn(false);
    }

    function it_will_throw_exception_when_getting_not_null_that_value_doesnt_validate(
        Value $value
    ) {
        $value->get(false)->willThrow(\InvalidArgumentException::class);
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet(false);
    }

    function it_will_return_null_for_null_or_empty_string()
    {
        $this->get(null)->shouldReturn(null);
        $this->get('')->shouldReturn(null);
    }

    function it_will_return_from_value_if_not_null_or_empty_string(Value $value)
    {
        $value->get('true')->willReturn(true);
        $this->get('true')->shouldReturn(true);

        $value->get(123)->willReturn(123);
        $this->get(123)->shouldReturn(123);
    }
}
