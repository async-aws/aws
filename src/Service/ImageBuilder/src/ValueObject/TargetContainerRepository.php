<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\ImageBuilder\Enum\ContainerRepositoryService;

/**
 * The container repository where the output container image is stored.
 */
final class TargetContainerRepository
{
    /**
     * Specifies the service in which this image was registered.
     *
     * @var ContainerRepositoryService::*
     */
    private $service;

    /**
     * The name of the container repository where the output container image is stored. This name is prefixed by the
     * repository location. For example, `<repository location url>/repository_name`.
     *
     * @var string
     */
    private $repositoryName;

    /**
     * @param array{
     *   service: ContainerRepositoryService::*,
     *   repositoryName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->service = $input['service'] ?? $this->throwException(new InvalidArgument('Missing required field "service".'));
        $this->repositoryName = $input['repositoryName'] ?? $this->throwException(new InvalidArgument('Missing required field "repositoryName".'));
    }

    /**
     * @param array{
     *   service: ContainerRepositoryService::*,
     *   repositoryName: string,
     * }|TargetContainerRepository $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }

    /**
     * @return ContainerRepositoryService::*
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
