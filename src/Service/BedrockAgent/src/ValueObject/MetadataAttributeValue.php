<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\MetadataValueType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the value of the metadata attribute. Choose a `type` and include the field that corresponds to it.
 */
final class MetadataAttributeValue
{
    /**
     * The type of the metadata attribute.
     *
     * @var MetadataValueType::*
     */
    private $type;

    /**
     * The value of the numeric metadata attribute.
     *
     * @var float|null
     */
    private $numberValue;

    /**
     * The value of the Boolean metadata attribute.
     *
     * @var bool|null
     */
    private $booleanValue;

    /**
     * The value of the string metadata attribute.
     *
     * @var string|null
     */
    private $stringValue;

    /**
     * An array of strings that define the value of the metadata attribute.
     *
     * @var string[]|null
     */
    private $stringListValue;

    /**
     * @param array{
     *   type: MetadataValueType::*,
     *   numberValue?: float|null,
     *   booleanValue?: bool|null,
     *   stringValue?: string|null,
     *   stringListValue?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->numberValue = $input['numberValue'] ?? null;
        $this->booleanValue = $input['booleanValue'] ?? null;
        $this->stringValue = $input['stringValue'] ?? null;
        $this->stringListValue = $input['stringListValue'] ?? null;
    }

    /**
     * @param array{
     *   type: MetadataValueType::*,
     *   numberValue?: float|null,
     *   booleanValue?: bool|null,
     *   stringValue?: string|null,
     *   stringListValue?: string[]|null,
     * }|MetadataAttributeValue $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBooleanValue(): ?bool
    {
        return $this->booleanValue;
    }

    public function getNumberValue(): ?float
    {
        return $this->numberValue;
    }

    /**
     * @return string[]
     */
    public function getStringListValue(): array
    {
        return $this->stringListValue ?? [];
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    /**
     * @return MetadataValueType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->type;
        if (!MetadataValueType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "MetadataValueType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->numberValue) {
            $payload['numberValue'] = $v;
        }
        if (null !== $v = $this->booleanValue) {
            $payload['booleanValue'] = (bool) $v;
        }
        if (null !== $v = $this->stringValue) {
            $payload['stringValue'] = $v;
        }
        if (null !== $v = $this->stringListValue) {
            $index = -1;
            $payload['stringListValue'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['stringListValue'][$index] = $listValue;
            }
        }

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
