<?php

namespace AsyncAws\SecretsManager\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\SecretsManager\Enum\FilterNameStringType;

/**
 * Allows you to add filters when you use the search function in Secrets Manager.
 */
final class Filter
{
    /**
     * Filters your list of secrets by a specific key.
     */
    private $key;

    /**
     * Filters your list of secrets by a specific value.
     */
    private $values;

    /**
     * @param array{
     *   Key?: null|FilterNameStringType::*,
     *   Values?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->values = $input['Values'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FilterNameStringType::*|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->key) {
            if (!FilterNameStringType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Key" for "%s". The value "%s" is not a valid "FilterNameStringType".', __CLASS__, $v));
            }
            $payload['Key'] = $v;
        }
        if (null !== $v = $this->values) {
            $index = -1;
            $payload['Values'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Values'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
