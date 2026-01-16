<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberLongValue extends Field
{
    /**
     * A value of long data type.
     *
     * @var int
     */
    private $longValue;

    /**
     * @param array{
     *   longValue: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->longValue = $input['longValue'] ?? $this->throwException(new InvalidArgument('Missing required field "longValue".'));
    }

    public function getLongValue(): int
    {
        return $this->longValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->longValue;
        $payload['longValue'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
