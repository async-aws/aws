<?php

namespace AsyncAws\RdsDataService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class FieldMemberArrayValue extends Field
{
    /**
     * An array of values.
     *
     * @var ArrayValue
     */
    private $arrayValue;

    /**
     * @param array{
     *   arrayValue: ArrayValue|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arrayValue = isset($input['arrayValue']) ? ArrayValue::create($input['arrayValue']) : $this->throwException(new InvalidArgument('Missing required field "arrayValue".'));
    }

    public function getArrayValue(): ArrayValue
    {
        return $this->arrayValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->arrayValue;
        $payload['arrayValue'] = $v->requestBody();

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
