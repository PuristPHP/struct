<?php

namespace spec\Purist\Struct;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\Constraint\Minimum;
use Purist\Struct\IntegerValue;
use Purist\Struct\Validation;

class ValidationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Validation::class);
    }

    function it_returns_errors_if_not_successful()
    {
        $this->beConstructedThrough('failed', ['error']);
        $this->hasErrors()->shouldReturn(true);
        $this->errors()->shouldReturn(['error']);
    }

    function it_returns_empty_error_array_if_successful()
    {
        $this->beConstructedThrough('successful');
        $this->hasErrors()->shouldReturn(false);
        $this->errors()->shouldReturn([]);
    }

    function it_will_merge_errors_from_previous_validations()
    {
        $this->beConstructedThrough('failed', ['first error', Validation::failed('second error')]);
        $this->hasErrors()->shouldReturn(true);
        $this->errors()->shouldReturn(['first error', 'second error']);
    }

    function it_can_construct_errors_from_values()
    {
        $this->beConstructedThrough('failedValue', ['integer', 15, null, new Minimum(16)]);
        $this->hasErrors()->shouldReturn(true);
        $this->errors()->shouldHaveCount(1);
    }
}
