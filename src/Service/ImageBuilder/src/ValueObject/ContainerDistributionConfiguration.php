<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Container distribution settings for encryption, licensing, and sharing in a specific Region.
 */
final class ContainerDistributionConfiguration
{
    /**
     * The description of the container distribution configuration.
     *
     * @var string|null
     */
    private $description;

    /**
     * Tags that are attached to the container distribution configuration.
     *
     * @var string[]|null
     */
    private $containerTags;

    /**
     * The destination repository for the container distribution configuration.
     *
     * @var TargetContainerRepository
     */
    private $targetRepository;

    /**
     * @param array{
     *   description?: string|null,
     *   containerTags?: string[]|null,
     *   targetRepository: TargetContainerRepository|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->description = $input['description'] ?? null;
        $this->containerTags = $input['containerTags'] ?? null;
        $this->targetRepository = isset($input['targetRepository']) ? TargetContainerRepository::create($input['targetRepository']) : $this->throwException(new InvalidArgument('Missing required field "targetRepository".'));
    }

    /**
     * @param array{
     *   description?: string|null,
     *   containerTags?: string[]|null,
     *   targetRepository: TargetContainerRepository|array,
     * }|ContainerDistributionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getContainerTags(): array
    {
        return $this->containerTags ?? [];
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTargetRepository(): TargetContainerRepository
    {
        return $this->targetRepository;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
