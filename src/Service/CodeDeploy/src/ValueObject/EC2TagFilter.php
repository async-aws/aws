<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\EC2TagFilterType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about an EC2 tag filter.
 */
final class EC2TagFilter
{
    /**
     * The tag filter key.
     *
     * @var string|null
     */
    private $key;

    /**
     * The tag filter value.
     *
     * @var string|null
     */
    private $value;

    /**
     * The tag filter type:
     *
     * - `KEY_ONLY`: Key only.
     * - `VALUE_ONLY`: Value only.
     * - `KEY_AND_VALUE`: Key and value.
     *
     * @var EC2TagFilterType::*|null
     */
    private $type;

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     *   Type?: EC2TagFilterType::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->value = $input['Value'] ?? null;
        $this->type = $input['Type'] ?? null;
    }

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     *   Type?: EC2TagFilterType::*|null,
     * }|EC2TagFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return EC2TagFilterType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->key) {
            $payload['Key'] = $v;
        }
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
        }
        if (null !== $v = $this->type) {
            if (!EC2TagFilterType::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "EC2TagFilterType".', __CLASS__, $v));
            }
            $payload['Type'] = $v;
        }

        return $payload;
    }
}
