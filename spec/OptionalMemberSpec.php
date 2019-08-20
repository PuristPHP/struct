<?php

namespace spec\Purist\Struct;

use Purist\Struct\IntegerValue;
use Purist\Struct\Member;
use Purist\Struct\OptionalMember;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionalMemberSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('someInteger', new IntegerValue());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OptionalMember::class);
        $this->shouldImplement(Member::class);
    }

    function it_returns_the_associated_name_value_from_the_input_array()
    {
        $this
            ->get(['someInteger' => '123', 'ignored' => true])
            ->shouldReturn(['someInteger' => 123]);
    }

    function it_will_return_null_when_name_is_missing_from_input_array()
    {
        $this->get(['anotherName' => 'test'])->shouldReturn(['someInteger' => null]);
    }

    function it_will_validate_input_array()
    {
        $this->validate(['someInteger' => '123'])->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_will_not_validate_incorrect_array()
    {
        $this->validate(['someInteger' => 'not'])->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_pass_missing_name_from_input_array()
    {
        $this->validate(['anotherInteger' => 345])->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }
}
