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
        $this->valid(['someInteger' => '123'])->shouldReturn(true);
    }

    function it_will_not_validate_incorrect_array()
    {
        $this->valid(['someInteger' => 'not'])->shouldReturn(false);
    }

    function it_will_pass_missing_name_from_input_array()
    {
        $this->valid(['anotherInteger' => 345])->shouldReturn(true);
    }
}
