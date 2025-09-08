<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Scheduler\Enum\LaunchType;
use AsyncAws\Scheduler\Enum\PropagateTags;

/**
 * The templated target type for the Amazon ECS `RunTask` [^1] API operation.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonECS/latest/APIReference/API_RunTask.html
 */
final class EcsParameters
{
    /**
     * The capacity provider strategy to use for the task.
     *
     * @var CapacityProviderStrategyItem[]|null
     */
    private $capacityProviderStrategy;

    /**
     * Specifies whether to enable Amazon ECS managed tags for the task. For more information, see Tagging Your Amazon ECS
     * Resources [^1] in the *Amazon ECS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonECS/latest/developerguide/ecs-using-tags.html
     *
     * @var bool|null
     */
    private $enableEcsManagedTags;

    /**
     * Whether or not to enable the execute command functionality for the containers in this task. If true, this enables
     * execute command functionality on all containers in the task.
     *
     * @var bool|null
     */
    private $enableExecuteCommand;

    /**
     * Specifies an ECS task group for the task. The maximum length is 255 characters.
     *
     * @var string|null
     */
    private $group;

    /**
     * Specifies the launch type on which your task is running. The launch type that you specify here must match one of the
     * launch type (compatibilities) of the target task. The `FARGATE` value is supported only in the Regions where Fargate
     * with Amazon ECS is supported. For more information, see AWS Fargate on Amazon ECS [^1] in the *Amazon ECS Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonECS/latest/developerguide/AWS_Fargate.html
     *
     * @var LaunchType::*|null
     */
    private $launchType;

    /**
     * This structure specifies the network configuration for an ECS task.
     *
     * @var NetworkConfiguration|null
     */
    private $networkConfiguration;

    /**
     * An array of placement constraint objects to use for the task. You can specify up to 10 constraints per task
     * (including constraints in the task definition and those specified at runtime).
     *
     * @var PlacementConstraint[]|null
     */
    private $placementConstraints;

    /**
     * The task placement strategy for a task or service.
     *
     * @var PlacementStrategy[]|null
     */
    private $placementStrategy;

    /**
     * Specifies the platform version for the task. Specify only the numeric portion of the platform version, such as
     * `1.1.0`.
     *
     * @var string|null
     */
    private $platformVersion;

    /**
     * Specifies whether to propagate the tags from the task definition to the task. If no value is specified, the tags are
     * not propagated. Tags can only be propagated to the task during task creation. To add tags to a task after task
     * creation, use Amazon ECS's `TagResource` [^1] API action.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonECS/latest/APIReference/API_TagResource.html
     *
     * @var PropagateTags::*|null
     */
    private $propagateTags;

    /**
     * The reference ID to use for the task.
     *
     * @var string|null
     */
    private $referenceId;

    /**
     * The metadata that you apply to the task to help you categorize and organize them. Each tag consists of a key and an
     * optional value, both of which you define. For more information, see `RunTask` [^1] in the *Amazon ECS API Reference*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonECS/latest/APIReference/API_RunTask.html
     *
     * @var array<string, string>[]|null
     */
    private $tags;

    /**
     * The number of tasks to create based on `TaskDefinition`. The default is `1`.
     *
     * @var int|null
     */
    private $taskCount;

    /**
     * The Amazon Resource Name (ARN) of the task definition to use if the event target is an Amazon ECS task.
     *
     * @var string
     */
    private $taskDefinitionArn;

    /**
     * @param array{
     *   CapacityProviderStrategy?: array<CapacityProviderStrategyItem|array>|null,
     *   EnableECSManagedTags?: bool|null,
     *   EnableExecuteCommand?: bool|null,
     *   Group?: string|null,
     *   LaunchType?: LaunchType::*|null,
     *   NetworkConfiguration?: NetworkConfiguration|array|null,
     *   PlacementConstraints?: array<PlacementConstraint|array>|null,
     *   PlacementStrategy?: array<PlacementStrategy|array>|null,
     *   PlatformVersion?: string|null,
     *   PropagateTags?: PropagateTags::*|null,
     *   ReferenceId?: string|null,
     *   Tags?: array[]|null,
     *   TaskCount?: int|null,
     *   TaskDefinitionArn: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->capacityProviderStrategy = isset($input['CapacityProviderStrategy']) ? array_map([CapacityProviderStrategyItem::class, 'create'], $input['CapacityProviderStrategy']) : null;
        $this->enableEcsManagedTags = $input['EnableECSManagedTags'] ?? null;
        $this->enableExecuteCommand = $input['EnableExecuteCommand'] ?? null;
        $this->group = $input['Group'] ?? null;
        $this->launchType = $input['LaunchType'] ?? null;
        $this->networkConfiguration = isset($input['NetworkConfiguration']) ? NetworkConfiguration::create($input['NetworkConfiguration']) : null;
        $this->placementConstraints = isset($input['PlacementConstraints']) ? array_map([PlacementConstraint::class, 'create'], $input['PlacementConstraints']) : null;
        $this->placementStrategy = isset($input['PlacementStrategy']) ? array_map([PlacementStrategy::class, 'create'], $input['PlacementStrategy']) : null;
        $this->platformVersion = $input['PlatformVersion'] ?? null;
        $this->propagateTags = $input['PropagateTags'] ?? null;
        $this->referenceId = $input['ReferenceId'] ?? null;
        $this->tags = $input['Tags'] ?? null;
        $this->taskCount = $input['TaskCount'] ?? null;
        $this->taskDefinitionArn = $input['TaskDefinitionArn'] ?? $this->throwException(new InvalidArgument('Missing required field "TaskDefinitionArn".'));
    }

    /**
     * @param array{
     *   CapacityProviderStrategy?: array<CapacityProviderStrategyItem|array>|null,
     *   EnableECSManagedTags?: bool|null,
     *   EnableExecuteCommand?: bool|null,
     *   Group?: string|null,
     *   LaunchType?: LaunchType::*|null,
     *   NetworkConfiguration?: NetworkConfiguration|array|null,
     *   PlacementConstraints?: array<PlacementConstraint|array>|null,
     *   PlacementStrategy?: array<PlacementStrategy|array>|null,
     *   PlatformVersion?: string|null,
     *   PropagateTags?: PropagateTags::*|null,
     *   ReferenceId?: string|null,
     *   Tags?: array[]|null,
     *   TaskCount?: int|null,
     *   TaskDefinitionArn: string,
     * }|EcsParameters $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CapacityProviderStrategyItem[]
     */
    public function getCapacityProviderStrategy(): array
    {
        return $this->capacityProviderStrategy ?? [];
    }

    public function getEnableEcsManagedTags(): ?bool
    {
        return $this->enableEcsManagedTags;
    }

    public function getEnableExecuteCommand(): ?bool
    {
        return $this->enableExecuteCommand;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @return LaunchType::*|null
     */
    public function getLaunchType(): ?string
    {
        return $this->launchType;
    }

    public function getNetworkConfiguration(): ?NetworkConfiguration
    {
        return $this->networkConfiguration;
    }

    /**
     * @return PlacementConstraint[]
     */
    public function getPlacementConstraints(): array
    {
        return $this->placementConstraints ?? [];
    }

    /**
     * @return PlacementStrategy[]
     */
    public function getPlacementStrategy(): array
    {
        return $this->placementStrategy ?? [];
    }

    public function getPlatformVersion(): ?string
    {
        return $this->platformVersion;
    }

    /**
     * @return PropagateTags::*|null
     */
    public function getPropagateTags(): ?string
    {
        return $this->propagateTags;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    /**
     * @return array<string, string>[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getTaskCount(): ?int
    {
        return $this->taskCount;
    }

    public function getTaskDefinitionArn(): string
    {
        return $this->taskDefinitionArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->capacityProviderStrategy) {
            $index = -1;
            $payload['CapacityProviderStrategy'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['CapacityProviderStrategy'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->enableEcsManagedTags) {
            $payload['EnableECSManagedTags'] = (bool) $v;
        }
        if (null !== $v = $this->enableExecuteCommand) {
            $payload['EnableExecuteCommand'] = (bool) $v;
        }
        if (null !== $v = $this->group) {
            $payload['Group'] = $v;
        }
        if (null !== $v = $this->launchType) {
            if (!LaunchType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "LaunchType" for "%s". The value "%s" is not a valid "LaunchType".', __CLASS__, $v));
            }
            $payload['LaunchType'] = $v;
        }
        if (null !== $v = $this->networkConfiguration) {
            $payload['NetworkConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->placementConstraints) {
            $index = -1;
            $payload['PlacementConstraints'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['PlacementConstraints'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->placementStrategy) {
            $index = -1;
            $payload['PlacementStrategy'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['PlacementStrategy'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->platformVersion) {
            $payload['PlatformVersion'] = $v;
        }
        if (null !== $v = $this->propagateTags) {
            if (!PropagateTags::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "PropagateTags" for "%s". The value "%s" is not a valid "PropagateTags".', __CLASS__, $v));
            }
            $payload['PropagateTags'] = $v;
        }
        if (null !== $v = $this->referenceId) {
            $payload['ReferenceId'] = $v;
        }
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;

                if (empty($listValue)) {
                    $payload['Tags'][$index] = new \stdClass();
                } else {
                    $payload['Tags'][$index] = [];
                    foreach ($listValue as $name => $mv) {
                        $payload['Tags'][$index][$name] = $mv;
                    }
                }
            }
        }
        if (null !== $v = $this->taskCount) {
            $payload['TaskCount'] = $v;
        }
        $v = $this->taskDefinitionArn;
        $payload['TaskDefinitionArn'] = $v;

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
