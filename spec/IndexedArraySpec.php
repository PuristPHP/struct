<?php

namespace spec\Purist\Struct;

use Purist\Struct\AnyValue;
use Purist\Struct\BooleanValue;
use Purist\Struct\FloatValue;
use Purist\Struct\IndexedArray;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\IntegerValue;
use Purist\Struct\StringValue;
use Purist\Struct\ValidationFailed;

class IndexedArraySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IndexedArray::class);
    }

    function it_will_validate_indexed_array_with_value()
    {
        $this->beConstructedWith(new StringValue());
        $this->validate(['string', 'anotherString'])->shouldReturn(true);
    }

    function it_will_not_validate_indexed_array_with_wrong_values()
    {
        $this->beConstructedWith(new FloatValue());
        $this->validate(['string', true])->shouldReturn(false);
    }

    function it_will_not_validate_associative_arrays_with_correct_values()
    {
        $this->beConstructedWith(new FloatValue());
        $this->validate([5.5, 'float' => 25.5])->shouldReturn(false);
    }

    function it_will_throw_exception_not_getting_indexed_array()
    {
        $this->beConstructedWith(new AnyValue());
        $this->shouldThrow(ValidationFailed::class)->duringGet(['string' => 'string']);
        $this->shouldThrow(ValidationFailed::class)->duringGet(true);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->beConstructedWith(new IntegerValue());
        $this->shouldThrow(ValidationFailed::class)->duringGet(['string']);
        $this->shouldThrow(ValidationFailed::class)->duringGet(['111hello']);
    }

    function it_will_return_valid_values_casted()
    {
        $this->beConstructedWith(new BooleanValue());
        $this->get([])->shouldReturn([]);
        $this->get(['on', 'off', true])->shouldReturn([true, false, true]);
    }

    function it_will_coerce_associated_arrays_and_stdClass_with_only_numbers_as_keys()
    {
        $this->beConstructedWith(new StringValue());
        $this->get((object) [0 => 'hello', 2 => 'bye', '3' => 'hi'])->shouldReturn(['hello', 'bye', 'hi']);
        $this->get([0 => 'hello', '2' => 'bye', 3 => 'hi'])->shouldReturn(['hello', 'bye', 'hi']);
    }
}
