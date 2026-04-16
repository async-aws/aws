<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Settings that Image Builder uses to configure the ECR repository and the output container images that Amazon
 * Inspector scans.
 */
final class EcrConfiguration
{
    /**
     * The name of the container repository that Amazon Inspector scans to identify findings for your container images. The
     * name includes the path for the repository location. If you don’t provide this information, Image Builder creates a
     * repository in your account named `image-builder-image-scanning-repository` for vulnerability scans of your output
     * container images.
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * Tags for Image Builder to apply to the output container image that Amazon Inspector scans. Tags can help you identify
     * and manage your scanned images.
     *
     * @var string[]|null
     */
    private $containerTags;

    /**
     * @param array{
     *   repositoryName?: string|null,
     *   containerTags?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->containerTags = $input['containerTags'] ?? null;
    }

    /**
     * @param array{
     *   repositoryName?: string|null,
     *   containerTags?: string[]|null,
     * }|EcrConfiguration $input
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

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }
}
