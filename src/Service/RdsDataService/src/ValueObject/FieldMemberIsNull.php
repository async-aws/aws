<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberIsNull extends Field
{
    /**
     * A NULL value.
     *
     * @var bool
     */
    private $isNull;

    /**
     * @param array{
     *   isNull: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->isNull = $input['isNull'] ?? $this->throwException(new InvalidArgument('Missing required field "isNull".'));
    }

    public function getIsNull(): bool
    {
        return $this->isNull;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->isNull;
        $payload['isNull'] = (bool) $v;

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
