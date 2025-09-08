<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Scheduler\Enum\AssignPublicIp;

/**
 * This structure specifies the VPC subnets and security groups for the task, and whether a public IP address is to be
 * used. This structure is relevant only for ECS tasks that use the awsvpc network mode.
 */
final class AwsVpcConfiguration
{
    /**
     * Specifies whether the task's elastic network interface receives a public IP address. You can specify `ENABLED` only
     * when `LaunchType` in `EcsParameters` is set to `FARGATE`.
     *
     * @var AssignPublicIp::*|null
     */
    private $assignPublicIp;

    /**
     * Specifies the security groups associated with the task. These security groups must all be in the same VPC. You can
     * specify as many as five security groups. If you do not specify a security group, the default security group for the
     * VPC is used.
     *
     * @var string[]|null
     */
    private $securityGroups;

    /**
     * Specifies the subnets associated with the task. These subnets must all be in the same VPC. You can specify as many as
     * 16 subnets.
     *
     * @var string[]
     */
    private $subnets;

    /**
     * @param array{
     *   AssignPublicIp?: AssignPublicIp::*|null,
     *   SecurityGroups?: string[]|null,
     *   Subnets: string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->assignPublicIp = $input['AssignPublicIp'] ?? null;
        $this->securityGroups = $input['SecurityGroups'] ?? null;
        $this->subnets = $input['Subnets'] ?? $this->throwException(new InvalidArgument('Missing required field "Subnets".'));
    }

    /**
     * @param array{
     *   AssignPublicIp?: AssignPublicIp::*|null,
     *   SecurityGroups?: string[]|null,
     *   Subnets: string[],
     * }|AwsVpcConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AssignPublicIp::*|null
     */
    public function getAssignPublicIp(): ?string
    {
        return $this->assignPublicIp;
    }

    /**
     * @return string[]
     */
    public function getSecurityGroups(): array
    {
        return $this->securityGroups ?? [];
    }

    /**
     * @return string[]
     */
    public function getSubnets(): array
    {
        return $this->subnets;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->assignPublicIp) {
            if (!AssignPublicIp::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "AssignPublicIp" for "%s". The value "%s" is not a valid "AssignPublicIp".', __CLASS__, $v));
            }
            $payload['AssignPublicIp'] = $v;
        }
        if (null !== $v = $this->securityGroups) {
            $index = -1;
            $payload['SecurityGroups'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SecurityGroups'][$index] = $listValue;
            }
        }
        $v = $this->subnets;

        $index = -1;
        $payload['Subnets'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Subnets'][$index] = $listValue;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
