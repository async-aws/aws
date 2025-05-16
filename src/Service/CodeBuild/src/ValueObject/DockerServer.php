<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\ComputeType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains docker server information.
 */
final class DockerServer
{
    /**
     * Information about the compute resources the docker server uses. Available values include:
     *
     * - `BUILD_GENERAL1_SMALL`: Use up to 4 GiB memory and 2 vCPUs for your docker server.
     * - `BUILD_GENERAL1_MEDIUM`: Use up to 8 GiB memory and 4 vCPUs for your docker server.
     * - `BUILD_GENERAL1_LARGE`: Use up to 16 GiB memory and 8 vCPUs for your docker server.
     * - `BUILD_GENERAL1_XLARGE`: Use up to 64 GiB memory and 32 vCPUs for your docker server.
     * - `BUILD_GENERAL1_2XLARGE`: Use up to 128 GiB memory and 64 vCPUs for your docker server.
     *
     * @var ComputeType::*
     */
    private $computeType;

    /**
     * A list of one or more security groups IDs.
     *
     * > Security groups configured for Docker servers should allow ingress network traffic from the VPC configured in the
     * > project. They should allow ingress on port 9876.
     *
     * @var string[]|null
     */
    private $securityGroupIds;

    /**
     * A DockerServerStatus object to use for this docker server.
     *
     * @var DockerServerStatus|null
     */
    private $status;

    /**
     * @param array{
     *   computeType: ComputeType::*,
     *   securityGroupIds?: null|string[],
     *   status?: null|DockerServerStatus|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->computeType = $input['computeType'] ?? $this->throwException(new InvalidArgument('Missing required field "computeType".'));
        $this->securityGroupIds = $input['securityGroupIds'] ?? null;
        $this->status = isset($input['status']) ? DockerServerStatus::create($input['status']) : null;
    }

    /**
     * @param array{
     *   computeType: ComputeType::*,
     *   securityGroupIds?: null|string[],
     *   status?: null|DockerServerStatus|array,
     * }|DockerServer $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ComputeType::*
     */
    public function getComputeType(): string
    {
        return $this->computeType;
    }

    /**
     * @return string[]
     */
    public function getSecurityGroupIds(): array
    {
        return $this->securityGroupIds ?? [];
    }

    public function getStatus(): ?DockerServerStatus
    {
        return $this->status;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
