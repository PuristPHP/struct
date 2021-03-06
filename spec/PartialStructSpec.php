<?php

namespace spec\Purist\Struct;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\AnyOfValues;
use Purist\Struct\AnyValue;
use Purist\Struct\BooleanValue;
use Purist\Struct\FloatValue;
use Purist\Struct\IntegerValue;
use Purist\Struct\NullableValue;
use Purist\Struct\OptionalMember;
use Purist\Struct\PartialStruct;
use Purist\Struct\RequiredMember;
use Purist\Struct\StringValue;
use Purist\Struct\ValidationFailed;

class PartialStructSpec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith(
            new RequiredMember(
                'aNumber',
                new AnyOfValues(new IntegerValue(), new FloatValue())
            ),
            new RequiredMember('anInteger', new IntegerValue()),
            new RequiredMember('aString', new StringValue()),
            new RequiredMember('aFloat', new FloatValue()),
            new RequiredMember(
                'aNullableFloat',
                new NullableValue(new FloatValue())
            ),
            new RequiredMember(
                'aStruct',
                new PartialStruct(
                    new RequiredMember('anInteger', new IntegerValue()),
                    new RequiredMember('aString', new StringValue()),
                    new RequiredMember('aFloat', new FloatValue()),
                    new RequiredMember('aBoolean', new BooleanValue()),
                    new OptionalMember('notSet', new AnyValue())
                )
            ),
            new OptionalMember('notSet', new AnyValue())
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PartialStruct::class);
    }

    function it_will_not_validate_invalid_arrays()
    {
        $this->validate(
            [
                'aNumber' => '123.5',
                'anInteger' => '123.456',
                'aString' => 'hello',
                'aFloat' => 456.789,
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => 'bye',
                    'aFloat' => 123.234,
                    'aBoolean' => false,
                ],
            ]
        )->callOnWrappedObject('hasErrors')->shouldReturn(true);
    }

    function it_will_validate_arrays()
    {
        $this->validate(
            [
                'aNumber' => '123.5',
                'anInteger' => '123',
                'aString' => 'hello',
                'aFloat' => 456.789,
                'aNullableFloat' => null,
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => '567',
                    'aFloat' => '123.234',
                    'aBoolean' => 'true',
                ],
            ]
        )->callOnWrappedObject('hasErrors')->shouldReturn(false);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(ValidationFailed::class)->duringGet(
            [
                'aNumber' => '123.5',
                'anInteger' => '123',
                'aString' => 'hello',
                'aFloat' => 456.789,
                'aNullableFloat' => '123.234',
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => 'bye',
                    'aFloat' => 123.234,
                    'aBoolean' => 123,
                ],
            ]
        );
        $this->shouldThrow(ValidationFailed::class)->duringGet(new \stdClass());
        $this->shouldThrow(ValidationFailed::class)->duringGet('not-an-array');
    }

    function it_will_return_a_validated_array_with_correct_types()
    {
        $this->get(
            [
                'aNumber' => '123.5',
                'anInteger' => '123',
                'aString' => 'hello',
                'aFloat' => '456',
                'aNullableFloat' => '123.345',
                'aStruct' => [
                    'anInteger' => '123',
                    'aString' => '567',
                    'aFloat' => '123.234',
                    'aBoolean' => 'on',
                    'dataNotInStructWillReturn' => true,
                ],
                'dataNotInStructWillReturn' => true,
            ]
        )->shouldReturn(
            [
                'aNumber' => 123.5,
                'anInteger' => 123,
                'aString' => 'hello',
                'aFloat' => 456.0,
                'aNullableFloat' => 123.345,
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => '567',
                    'aFloat' => 123.234,
                    'aBoolean' => true,
                    'dataNotInStructWillReturn' => true,
                    'notSet' => null,
                ],
                'dataNotInStructWillReturn' => true,
                'notSet' => null,
            ]
        );
    }
}
