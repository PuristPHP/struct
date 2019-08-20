<?php
declare(strict_types=1);

namespace Purist\Struct;

abstract class AbstractStruct
{
    private $data;
    private $struct;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->struct = $this->struct();
    }

    abstract protected function struct(): Struct;

    public function valid(): Validation
    {
        return $this->struct->validate($this->data);
    }

    /**
     * @throws ValidationFailed
     */
    public function toArray(): array
    {
        return $this->struct->get($this->data);
    }
}
