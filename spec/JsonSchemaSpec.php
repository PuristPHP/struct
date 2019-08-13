<?php

namespace spec\Purist\Struct;

use Purist\Struct\JsonSchema;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonSchemaSpec extends ObjectBehavior
{
    function let()
    {

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JsonSchema::class);
    }
}
