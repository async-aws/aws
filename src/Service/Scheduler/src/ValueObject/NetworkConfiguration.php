<?php

namespace AsyncAws\Scheduler\ValueObject;

/**
 * Specifies the network configuration for an ECS task.
 */
final class NetworkConfiguration
{
    /**
     * Specifies the Amazon VPC subnets and security groups for the task, and whether a public IP address is to be used.
     * This structure is relevant only for ECS tasks that use the awsvpc network mode.
     *
     * @var AwsVpcConfiguration|null
     */
    private $awsvpcConfiguration;

    /**
     * @param array{
     *   awsvpcConfiguration?: AwsVpcConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->awsvpcConfiguration = isset($input['awsvpcConfiguration']) ? AwsVpcConfiguration::create($input['awsvpcConfiguration']) : null;
    }

    /**
     * @param array{
     *   awsvpcConfiguration?: AwsVpcConfiguration|array|null,
     * }|NetworkConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAwsvpcConfiguration(): ?AwsVpcConfiguration
    {
        return $this->awsvpcConfiguration;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->awsvpcConfiguration) {
            $payload['awsvpcConfiguration'] = $v->requestBody();
        }

        return $payload;
    }
}
