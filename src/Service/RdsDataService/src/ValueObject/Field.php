<?php

namespace AsyncAws\RdsDataService\ValueObject;

final class Field
{
    /**
     * An array of values.
     */
    private $arrayValue;

    /**
     * A value of BLOB data type.
     */
    private $blobValue;

    /**
     * A value of Boolean data type.
     */
    private $booleanValue;

    /**
     * A value of double data type.
     */
    private $doubleValue;

    /**
     * A NULL value.
     */
    private $isNull;

    /**
     * A value of long data type.
     */
    private $longValue;

    /**
     * A value of string data type.
     */
    private $stringValue;

    /**
     * @param array{
     *   arrayValue?: null|\AsyncAws\RdsDataService\ValueObject\ArrayValue|array,
     *   blobValue?: null|string,
     *   booleanValue?: null|bool,
     *   doubleValue?: null|float,
     *   isNull?: null|bool,
     *   longValue?: null|string,
     *   stringValue?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arrayValue = isset($input['arrayValue']) ? ArrayValue::create($input['arrayValue']) : null;
        $this->blobValue = $input['blobValue'] ?? null;
        $this->booleanValue = $input['booleanValue'] ?? null;
        $this->doubleValue = $input['doubleValue'] ?? null;
        $this->isNull = $input['isNull'] ?? null;
        $this->longValue = $input['longValue'] ?? null;
        $this->stringValue = $input['stringValue'] ?? null;
    }

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
        if (null !== $v = $this->arrayValue) {
            $payload['arrayValue'] = $v->requestBody();
        }
        if (null !== $v = $this->blobValue) {
            $payload['blobValue'] = base64_encode($v);
        }
        if (null !== $v = $this->booleanValue) {
            $payload['booleanValue'] = (bool) $v;
        }
        if (null !== $v = $this->doubleValue) {
            $payload['doubleValue'] = $v;
        }
        if (null !== $v = $this->isNull) {
            $payload['isNull'] = (bool) $v;
        }
        if (null !== $v = $this->longValue) {
            $payload['longValue'] = $v;
        }
        if (null !== $v = $this->stringValue) {
            $payload['stringValue'] = $v;
        }

        return $payload;
    }
}
