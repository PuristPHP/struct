<?php

namespace spec\Purist\Struct\Values;

use Purist\Struct\Values\ArrayValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Purist\Struct\Values\BooleanValue;
use Purist\Struct\Values\FloatValue;
use Purist\Struct\Values\IntegerValue;
use Purist\Struct\Values\NullableValue;
use Purist\Struct\Values\StringValue;

class ArrayValueSpec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith(
            [
                'anInteger' => new IntegerValue(),
                'aString' => new StringValue(),
                'aFloat' => new FloatValue(),
                'aNullableFloat' => new NullableValue(new FloatValue()),
                'aStruct' => new ArrayValue(
                    [
                        'anInteger' => new IntegerValue(),
                        'aString' => new StringValue(),
                        'aFloat' => new FloatValue(),
                        'aBoolean' => new BooleanValue(),
                    ]
                ),
            ]
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(\Purist\Struct\Values\ArrayValue::class);
    }

    function it_will_not_validate_invalid_arrays()
    {
        $this->validate(
            [
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
        )->shouldReturn(false);
    }

    function it_will_validate_arrays()
    {
        $this->validate(
            [
                'anInteger' => '123',
                'aString' => 'hello',
                'aFloat' => 456.789,
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => '567',
                    'aFloat' => '123.234',
                    'aBoolean' => 'true',
                ],
                'anotherStruct' => new \ArrayObject([
                    'aString' => 'trololololol',
                ]),
            ]
        )->shouldReturn(true);
    }

    function it_will_throw_exception_getting_invalid_values()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet(
            [
                'anInteger' => '123',
                'aString' => 'hello',
                'aFloat' => 456.789,
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => 'bye',
                    'aFloat' => 123.234,
                    'aBoolean' => 123,
                ],
            ]
        );
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet(new \stdClass());
        $this->shouldThrow(\InvalidArgumentException::class)->duringGet('not-an-array');
    }

    function it_will_return_a_validated_array_with_correct_types()
    {
        $this->get(
            [
                'anInteger' => '123',
                'aString' => 'hello',
                'aFloat' => '456',
                'aStruct' => [
                    'anInteger' => '123',
                    'aString' => '567',
                    'aFloat' => '123.234',
                    'aBoolean' => 'on',
                ],
            ]
        )->shouldReturn(
            [
                'anInteger' => 123,
                'aString' => 'hello',
                'aFloat' => 456.0,
                'aStruct' => [
                    'anInteger' => 123,
                    'aString' => '567',
                    'aFloat' => 123.234,
                    'aBoolean' => true,
                ],
            ]
        );
    }
}
