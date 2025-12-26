<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberBooleanValue extends Field
{
    /**
     * A value of Boolean data type.
     *
     * @var bool
     */
    private $booleanValue;

    /**
     * @param array{
     *   booleanValue: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->booleanValue = $input['booleanValue'] ?? $this->throwException(new InvalidArgument('Missing required field "booleanValue".'));
    }

    public function getBooleanValue(): bool
    {
        return $this->booleanValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->booleanValue;
        $payload['booleanValue'] = (bool) $v;

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
