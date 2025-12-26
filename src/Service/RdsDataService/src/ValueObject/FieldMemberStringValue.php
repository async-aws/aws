<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberStringValue extends Field
{
    /**
     * A value of string data type.
     *
     * @var string
     */
    private $stringValue;

    /**
     * @param array{
     *   stringValue: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stringValue = $input['stringValue'] ?? $this->throwException(new InvalidArgument('Missing required field "stringValue".'));
    }

    public function getStringValue(): string
    {
        return $this->stringValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->stringValue;
        $payload['stringValue'] = $v;

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
