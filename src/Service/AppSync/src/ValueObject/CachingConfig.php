<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The caching configuration for the resolver.
 */
final class CachingConfig
{
    /**
     * The TTL in seconds for a resolver that has caching enabled.
     */
    private $ttl;

    /**
     * The caching keys for a resolver that has caching enabled.
     */
    private $cachingKeys;

    /**
     * @param array{
     *   ttl?: null|string,
     *   cachingKeys?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ttl = $input['ttl'] ?? null;
        $this->cachingKeys = $input['cachingKeys'] ?? null;
    }

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

    public function getTtl(): ?string
    {
        return $this->ttl;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ttl) {
            $payload['ttl'] = $v;
        }
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
