<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * A container encapsulates the runtime environment for an application.
 */
final class Container
{
    /**
     * Containers and container images are Region-specific. This is the Region context for the container.
     *
     * @var string|null
     */
    private $region;

    /**
     * A list of URIs for containers created in the context Region.
     *
     * @var string[]|null
     */
    private $imageUris;

    /**
     * @param array{
     *   region?: string|null,
     *   imageUris?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->region = $input['region'] ?? null;
        $this->imageUris = $input['imageUris'] ?? null;
    }

    /**
     * @param array{
     *   region?: string|null,
     *   imageUris?: string[]|null,
     * }|Container $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getImageUris(): array
    {
        return $this->imageUris ?? [];
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }
}
