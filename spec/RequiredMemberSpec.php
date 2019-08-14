<?php

namespace spec\Purist\Struct;

use Purist\Struct\AnyValue;
use Purist\Struct\IntegerValue;
use Purist\Struct\RequiredMember;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\ValidationFailed;

class RequiredMemberSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('test', new AnyValue());
        $this->shouldHaveType(RequiredMember::class);
    }

    function it_returns_the_associated_name_value_from_the_input_array()
    {
        $this->beConstructedWith('someInteger', new IntegerValue());
        $this
            ->get(['someInteger' => '123', 'ignored' => true])
            ->shouldReturn(['someInteger' => 123]);
    }

    function it_will_throw_exception_when_name_is_missing_from_input_array()
    {
        $this->beConstructedWith('missingName', new AnyValue());
        $this->shouldThrow(ValidationFailed::class)->duringGet(['anotherName' => 'test']);
    }

    function it_will_validate_input_array()
    {
        $this->beConstructedWith('someInteger', new IntegerValue());
        $this->valid(['someInteger' => '123'])->shouldReturn(true);
    }

    function it_will_not_validate_incorrect_array()
    {
        $this->beConstructedWith('someInteger', new IntegerValue());
        $this->valid(['someInteger' => 'not'])->shouldReturn(false);
    }
}
