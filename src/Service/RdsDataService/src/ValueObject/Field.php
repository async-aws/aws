<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * Contains a value.
 */
final class Field
{
    /**
     * A NULL value.
     *
     * @var bool|null
     */
    private $isNull;

    /**
     * A value of Boolean data type.
     *
     * @var bool|null
     */
    private $booleanValue;

    /**
     * A value of long data type.
     *
     * @var int|null
     */
    private $longValue;

    /**
     * A value of double data type.
     *
     * @var float|null
     */
    private $doubleValue;

    /**
     * A value of string data type.
     *
     * @var string|null
     */
    private $stringValue;

    /**
     * A value of BLOB data type.
     *
     * @var string|null
     */
    private $blobValue;

    /**
     * An array of values.
     *
     * @var ArrayValue|null
     */
    private $arrayValue;

    /**
     * @param array{
     *   isNull?: bool|null,
     *   booleanValue?: bool|null,
     *   longValue?: int|null,
     *   doubleValue?: float|null,
     *   stringValue?: string|null,
     *   blobValue?: string|null,
     *   arrayValue?: ArrayValue|array|null,
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
     *   isNull?: bool|null,
     *   booleanValue?: bool|null,
     *   longValue?: int|null,
     *   doubleValue?: float|null,
     *   stringValue?: string|null,
     *   blobValue?: string|null,
     *   arrayValue?: ArrayValue|array|null,
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

    public function getLongValue(): ?int
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
