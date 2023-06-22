<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * Contains a value.
 */
final class Field
{
    /**
     * A NULL value.
     */
    private $isNull;

    /**
     * A value of Boolean data type.
     */
    private $booleanValue;

    /**
     * A value of long data type.
     */
    private $longValue;

    /**
     * A value of double data type.
     */
    private $doubleValue;

    /**
     * A value of string data type.
     */
    private $stringValue;

    /**
     * A value of BLOB data type.
     */
    private $blobValue;

    /**
     * An array of values.
     */
    private $arrayValue;

    /**
     * @param array{
     *   isNull?: null|bool,
     *   booleanValue?: null|bool,
     *   longValue?: null|string,
     *   doubleValue?: null|float,
     *   stringValue?: null|string,
     *   blobValue?: null|string,
     *   arrayValue?: null|ArrayValue|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->isNull = $input['isNull'] ?? null;
        $this->booleanValue = $input['booleanValue'] ?? null;
        $this->longValue = $input['longValue'] ?? null;
        $this->doubleValue = $input['doubleValue'] ?? null;
        $this->stringValue = $input['stringValue'] ?? null;
        $this->blobValue = $input['blobValue'] ?? null;
        $this->arrayValue = isset($input['arrayValue']) ? ArrayValue::create($input['arrayValue']) : null;
    }

    /**
     * @param array{
     *   isNull?: null|bool,
     *   booleanValue?: null|bool,
     *   longValue?: null|string,
     *   doubleValue?: null|float,
     *   stringValue?: null|string,
     *   blobValue?: null|string,
     *   arrayValue?: null|ArrayValue|array,
     * }|Field $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArrayValue(): ?ArrayValue
    {
        return $this->arrayValue;
    }

    public function getBlobValue(): ?string
    {
        return $this->blobValue;
    }

    public function getBooleanValue(): ?bool
    {
        return $this->booleanValue;
    }

    public function getDoubleValue(): ?float
    {
        return $this->doubleValue;
    }

    public function getIsNull(): ?bool
    {
        return $this->isNull;
    }

    public function getLongValue(): ?string
    {
        return $this->longValue;
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->isNull) {
            $payload['isNull'] = (bool) $v;
        }
        if (null !== $v = $this->booleanValue) {
            $payload['booleanValue'] = (bool) $v;
        }
        if (null !== $v = $this->longValue) {
            $payload['longValue'] = $v;
        }
        if (null !== $v = $this->doubleValue) {
            $payload['doubleValue'] = $v;
        }
        if (null !== $v = $this->stringValue) {
            $payload['stringValue'] = $v;
        }
        if (null !== $v = $this->blobValue) {
            $payload['blobValue'] = base64_encode($v);
        }
        if (null !== $v = $this->arrayValue) {
            $payload['arrayValue'] = $v->requestBody();
        }

        return $payload;
    }
}
