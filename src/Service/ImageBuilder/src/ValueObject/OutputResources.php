<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * The resources produced by this image.
 */
final class OutputResources
{
    /**
     * The Amazon EC2 AMIs created by this image. The list contains one entry per AMI, including copies that distribution
     * created in each target Amazon Web Services Region and account.
     *
     * @var Ami[]|null
     */
    private $amis;

    /**
     * The container images that Image Builder created when it built this image, stored in the output Amazon ECR repository.
     *
     * @var Container[]|null
     */
    private $containers;

    /**
     * @param array{
     *   amis?: array<Ami|array>|null,
     *   containers?: array<Container|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->amis = isset($input['amis']) ? array_map([Ami::class, 'create'], $input['amis']) : null;
        $this->containers = isset($input['containers']) ? array_map([Container::class, 'create'], $input['containers']) : null;
    }

    /**
     * @param array{
     *   amis?: array<Ami|array>|null,
     *   containers?: array<Container|array>|null,
     * }|OutputResources $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Ami[]
     */
    public function getAmis(): array
    {
        return $this->amis ?? [];
    }

    /**
     * @return Container[]
     */
    public function getContainers(): array
    {
        return $this->containers ?? [];
    }
}
