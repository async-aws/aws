<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\LogicException;

final class ArrayValueMemberUnknownToSdk extends ArrayValue
{
    /**
     * @var array<string, mixed>
     */
    private $unknown;

    /**
     * @internal
     *
     * @param array<string, mixed> $input
     */
    public function __construct(array $input)
    {
        $this->unknown = $input;
    }

    /**
     * @return array<string, mixed>
     */
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
