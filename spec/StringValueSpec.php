<?php

namespace spec\Purist\Struct;

use Purist\Struct\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\ValidationFailed;

class StringValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StringValue::class);
    }

    function it_will_not_validate_invalid_strings()
    {
        $this->validate(new \stdClass())->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate([])->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(true)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(false)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(123)->callOnWrappedObject('hasErrors')->shouldReturn(true);
        $this->validate(null)->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_validate_strings()
    {
        $this->validate('123')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate('@@@hello@@@')->callOnWrappedObject('hasErrors')->shouldReturn(false);
        $this->validate(
            new class {
                public function __toString()
                {
                    return 'string';
                }
            }
            )->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(ValidationFailed::class)->duringGet(123);
        $this->shouldThrow(ValidationFailed::class)->duringGet(true);
    }

    function it_will_return_valid_string_value()
    {
        $this->get('123')->shouldReturn('123');
        $this->get('@@@hello@@@')->shouldReturn('@@@hello@@@');
    }
}
