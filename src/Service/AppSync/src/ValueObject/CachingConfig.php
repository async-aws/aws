<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The caching configuration for a resolver that has caching activated.
 */
final class CachingConfig
{
    /**
     * The TTL in seconds for a resolver that has caching activated.
     *
     * Valid values are 1–3,600 seconds.
     */
    private $ttl;

    /**
     * The caching keys for a resolver that has caching activated.
     *
     * Valid values are entries from the `$context.arguments`, `$context.source`, and `$context.identity` maps.
     */
    private $cachingKeys;

    /**
     * @param array{
     *   ttl: int,
     *   cachingKeys?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ttl = $input['ttl'] ?? null;
        $this->cachingKeys = $input['cachingKeys'] ?? null;
    }

    /**
     * @param array{
     *   ttl: int,
     *   cachingKeys?: null|string[],
     * }|CachingConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getCachingKeys(): array
    {
        return $this->cachingKeys ?? [];
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ttl) {
            throw new InvalidArgument(sprintf('Missing parameter "ttl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ttl'] = $v;
        if (null !== $v = $this->cachingKeys) {
            $index = -1;
            $payload['cachingKeys'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['cachingKeys'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
