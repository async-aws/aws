<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\LogicException;

class FieldMemberUnknownToSdk extends Field
{
    /**
     * @var array<string, mixed>
     */
    private $unknown;

    public function __construct(array $input)
    {
        $this->unknown = $input;
    }

    public function getUnknown(): array
    {
        return $this->unknown;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        throw new LogicException('request can not be generated for unknown object');
    }
}
