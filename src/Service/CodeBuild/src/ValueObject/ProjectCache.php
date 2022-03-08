<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\CacheMode;
use AsyncAws\CodeBuild\Enum\CacheType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the cache for the build.
 */
final class ProjectCache
{
    /**
     * The type of cache used by the build project. Valid values include:.
     */
    private $type;

    /**
     * Information about the cache location:.
     */
    private $location;

    /**
     * An array of strings that specify the local cache modes. You can use one or more local cache modes at the same time.
     * This is only used for `LOCAL` cache types.
     */
    private $modes;

    /**
     * @param array{
     *   type: CacheType::*,
     *   location?: null|string,
     *   modes?: null|list<CacheMode::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? null;
        $this->location = $input['location'] ?? null;
        $this->modes = $input['modes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @return list<CacheMode::*>
     */
    public function getModes(): array
    {
        return $this->modes ?? [];
    }

    /**
     * @return CacheType::*
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
        if (null === $v = $this->type) {
            throw new InvalidArgument(sprintf('Missing parameter "type" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!CacheType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "CacheType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->location) {
            $payload['location'] = $v;
        }
        if (null !== $v = $this->modes) {
            $index = -1;
            $payload['modes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!CacheMode::exists($listValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "modes" for "%s". The value "%s" is not a valid "CacheMode".', __CLASS__, $listValue));
                }
                $payload['modes'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
