<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Settings that Image Builder uses to configure the ECR repository and the output container images that Amazon
 * Inspector scans.
 */
final class EcrConfiguration
{
    /**
     * The name of the container repository where Image Builder pushes the container image for the vulnerability scan.
     * Provide the repository name only (a namespace path is allowed, but not the registry hostname); the repository must
     * already exist in your account. If you don't specify a repository name, Image Builder creates the default repository
     * `image-builder-image-scanning-repository` in your account.
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
