<?php

namespace spec\Purist\Struct;

use Purist\Struct\AnyOfValues;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\FloatValue;
use Purist\Struct\IntegerValue;
use Purist\Struct\Value;

class AnyOfValuesSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new IntegerValue(), new FloatValue());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnyOfValues::class);
        $this->shouldImplement(Value::class);
    }

    function it_passes_if_any_value_is_valid()
    {
        $this->validate('12.0')->shouldReturn(true);
        $this->validate(12.5)->shouldReturn(true);
        $this->validate(12)->shouldReturn(true);
    }

    function it_fails_if_non_of_the_values_is_valid()
    {
        $this->validate('hello')->shouldReturn(false);
        $this->validate(true)->shouldReturn(false);
    }

    function it_will_return_first_valid_value_in_format()
    {
        $this->get('123.1')->shouldReturn(123.1);
        $this->get('123')->shouldReturn(123);
    }

    function it_throws_if_no_values_validates()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during(
            'get',
            ['hello']
        );
    }
}
