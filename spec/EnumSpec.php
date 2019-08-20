<?php

namespace spec\Purist\Struct;

use Purist\Struct\Enum;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\ValidationFailed;
use Purist\Struct\Value;

class EnumSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['ocramius', 'yegor256', null, 123, [123, 456]]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Enum::class);
        $this->shouldImplement(Value::class);
    }

    function it_will_fail_passing_empty_array()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_validates_against_set_of_values()
    {
        $this->validate('ocramius')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('yegor256')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(null)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(123)->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate([123, 456])->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_fails_validation_with_values_not_in_set()
    {
        $this->validate('taylor')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate('adam')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate('haha123')->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(123.0)->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_get_the_value_if_valid()
    {
        $this->get('ocramius')->shouldReturn('ocramius');
        $this->get([123, 456])->shouldReturn([123, 456]);
    }

    function it_will_throw_if_getting_value_outside_of_enum()
    {
        $this->shouldThrow(ValidationFailed::class)->during('get', [23, 456]);
        $this->shouldThrow(ValidationFailed::class)->during('get', ['otwell']);
        $this->shouldThrow(ValidationFailed::class)->during('get', ['wathan']);
    }
}
