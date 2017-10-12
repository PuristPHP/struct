<?php

namespace spec\Purist\Struct;

use Purist\Struct\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StringValue::class);
    }

    function it_will_not_validate_invalid_strings()
    {
        $this->validate(new \stdClass())->shouldReturn(false);
        $this->validate([])->shouldReturn(false);
        $this->validate(true)->shouldReturn(false);
        $this->validate(false)->shouldReturn(false);
        $this->validate(123)->shouldReturn(false);
        $this->validate(null)->shouldReturn(false);
    }

    function it_will_validate_strings()
    {
        $this->validate('123')->shouldReturn(true);
        $this->validate('@@@hello@@@')->shouldReturn(true);
        $this->validate(
            new class {
                public function __toString()
                {
                    return 'string';
                }
            }
            )->shouldReturn(true);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet(123);
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet(true);
    }

    function it_will_return_valid_string_value()
    {
        $this->get('123')->shouldReturn('123');
        $this->get('@@@hello@@@')->shouldReturn('@@@hello@@@');
    }
}
