<?php

namespace AsyncAws\Scheduler\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Scheduler\Enum\ActionAfterCompletion;
use AsyncAws\Scheduler\Enum\AssignPublicIp;
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;
use AsyncAws\Scheduler\Enum\LaunchType;
use AsyncAws\Scheduler\Enum\PlacementConstraintType;
use AsyncAws\Scheduler\Enum\PlacementStrategyType;
use AsyncAws\Scheduler\Enum\PropagateTags;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\ValueObject\AwsVpcConfiguration;
use AsyncAws\Scheduler\ValueObject\CapacityProviderStrategyItem;
use AsyncAws\Scheduler\ValueObject\DeadLetterConfig;
use AsyncAws\Scheduler\ValueObject\EcsParameters;
use AsyncAws\Scheduler\ValueObject\EventBridgeParameters;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\KinesisParameters;
use AsyncAws\Scheduler\ValueObject\NetworkConfiguration;
use AsyncAws\Scheduler\ValueObject\PlacementConstraint;
use AsyncAws\Scheduler\ValueObject\PlacementStrategy;
use AsyncAws\Scheduler\ValueObject\RetryPolicy;
use AsyncAws\Scheduler\ValueObject\SageMakerPipelineParameter;
use AsyncAws\Scheduler\ValueObject\SageMakerPipelineParameters;
use AsyncAws\Scheduler\ValueObject\SqsParameters;
use AsyncAws\Scheduler\ValueObject\Target;

class GetScheduleOutput extends Result
{
    /**
     * Indicates the action that EventBridge Scheduler applies to the schedule after the schedule completes invoking the
     * target.
     *
     * @var ActionAfterCompletion::*|null
     */
    private $actionAfterCompletion;

    /**
     * The Amazon Resource Name (ARN) of the schedule.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The time at which the schedule was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * The description of the schedule.
     *
     * @var string|null
     */
    private $description;

    /**
     * The date, in UTC, before which the schedule can invoke its target. Depending on the schedule's recurrence expression,
     * invocations might stop on, or before, the `EndDate` you specify. EventBridge Scheduler ignores `EndDate` for one-time
     * schedules.
     *
     * @var \DateTimeImmutable|null
     */
    private $endDate;

    /**
     * Allows you to configure a time window during which EventBridge Scheduler invokes the schedule.
     *
     * @var FlexibleTimeWindow|null
     */
    private $flexibleTimeWindow;

    /**
     * The name of the schedule group associated with this schedule.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The ARN for a customer managed KMS Key that is be used to encrypt and decrypt your data.
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * The time at which the schedule was last modified.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModificationDate;

    /**
     * The name of the schedule.
     *
     * @var string|null
     */
    private $name;

    /**
     * The expression that defines when the schedule runs. The following formats are supported.
     *
     * - `at` expression - `at(yyyy-mm-ddThh:mm:ss)`
     * - `rate` expression - `rate(value unit)`
     * - `cron` expression - `cron(fields)`
     *
     * You can use `at` expressions to create one-time schedules that invoke a target once, at the time and in the time
     * zone, that you specify. You can use `rate` and `cron` expressions to create recurring schedules. Rate-based schedules
     * are useful when you want to invoke a target at regular intervals, such as every 15 minutes or every five days.
     * Cron-based schedules are useful when you want to invoke a target periodically at a specific time, such as at 8:00 am
     * (UTC+0) every 1st day of the month.
     *
     * A `cron` expression consists of six fields separated by white spaces: `(minutes hours day_of_month month day_of_week
     * year)`.
     *
     * A `rate` expression consists of a *value* as a positive integer, and a *unit* with the following options: `minute` |
     * `minutes` | `hour` | `hours` | `day` | `days`
     *
     * For more information and examples, see Schedule types on EventBridge Scheduler [^1] in the *EventBridge Scheduler
     * User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/scheduler/latest/UserGuide/schedule-types.html
     *
     * @var string|null
     */
    private $scheduleExpression;

    /**
     * The timezone in which the scheduling expression is evaluated.
     *
     * @var string|null
     */
    private $scheduleExpressionTimezone;

    /**
     * The date, in UTC, after which the schedule can begin invoking its target. Depending on the schedule's recurrence
     * expression, invocations might occur on, or after, the `StartDate` you specify. EventBridge Scheduler ignores
     * `StartDate` for one-time schedules.
     *
     * @var \DateTimeImmutable|null
     */
    private $startDate;

    /**
     * Specifies whether the schedule is enabled or disabled.
     *
     * @var ScheduleState::*|null
     */
    private $state;

    /**
     * The schedule target.
     *
     * @var Target|null
     */
    private $target;

    /**
     * @return ActionAfterCompletion::*|null
     */
    public function getActionAfterCompletion(): ?string
    {
        $this->initialize();

        return $this->actionAfterCompletion;
    }

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->arn;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->creationDate;
    }

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->description;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->endDate;
    }

    public function getFlexibleTimeWindow(): ?FlexibleTimeWindow
    {
        $this->initialize();

        return $this->flexibleTimeWindow;
    }

    public function getGroupName(): ?string
    {
        $this->initialize();

        return $this->groupName;
    }

    public function getKmsKeyArn(): ?string
    {
        $this->initialize();

        return $this->kmsKeyArn;
    }

    public function getLastModificationDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->lastModificationDate;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    public function getScheduleExpression(): ?string
    {
        $this->initialize();

        return $this->scheduleExpression;
    }

    public function getScheduleExpressionTimezone(): ?string
    {
        $this->initialize();

        return $this->scheduleExpressionTimezone;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->startDate;
    }

    /**
     * @return ScheduleState::*|null
     */
    public function getState(): ?string
    {
        $this->initialize();

        return $this->state;
    }

    public function getTarget(): ?Target
    {
        $this->initialize();

        return $this->target;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->actionAfterCompletion = isset($data['ActionAfterCompletion']) ? (!ActionAfterCompletion::exists((string) $data['ActionAfterCompletion']) ? ActionAfterCompletion::UNKNOWN_TO_SDK : (string) $data['ActionAfterCompletion']) : null;
        $this->arn = isset($data['Arn']) ? (string) $data['Arn'] : null;
        $this->creationDate = isset($data['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['CreationDate']))) ? $d : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->endDate = isset($data['EndDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['EndDate']))) ? $d : null;
        $this->flexibleTimeWindow = empty($data['FlexibleTimeWindow']) ? null : $this->populateResultFlexibleTimeWindow($data['FlexibleTimeWindow']);
        $this->groupName = isset($data['GroupName']) ? (string) $data['GroupName'] : null;
        $this->kmsKeyArn = isset($data['KmsKeyArn']) ? (string) $data['KmsKeyArn'] : null;
        $this->lastModificationDate = isset($data['LastModificationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['LastModificationDate']))) ? $d : null;
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->scheduleExpression = isset($data['ScheduleExpression']) ? (string) $data['ScheduleExpression'] : null;
        $this->scheduleExpressionTimezone = isset($data['ScheduleExpressionTimezone']) ? (string) $data['ScheduleExpressionTimezone'] : null;
        $this->startDate = isset($data['StartDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['StartDate']))) ? $d : null;
        $this->state = isset($data['State']) ? (!ScheduleState::exists((string) $data['State']) ? ScheduleState::UNKNOWN_TO_SDK : (string) $data['State']) : null;
        $this->target = empty($data['Target']) ? null : $this->populateResultTarget($data['Target']);
    }

    private function populateResultAwsVpcConfiguration(array $json): AwsVpcConfiguration
    {
        return new AwsVpcConfiguration([
            'AssignPublicIp' => isset($json['AssignPublicIp']) ? (!AssignPublicIp::exists((string) $json['AssignPublicIp']) ? AssignPublicIp::UNKNOWN_TO_SDK : (string) $json['AssignPublicIp']) : null,
            'SecurityGroups' => !isset($json['SecurityGroups']) ? null : $this->populateResultSecurityGroups($json['SecurityGroups']),
            'Subnets' => $this->populateResultSubnets($json['Subnets']),
        ]);
    }

    /**
     * @return CapacityProviderStrategyItem[]
     */
    private function populateResultCapacityProviderStrategy(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCapacityProviderStrategyItem($item);
        }

        return $items;
    }

    private function populateResultCapacityProviderStrategyItem(array $json): CapacityProviderStrategyItem
    {
        return new CapacityProviderStrategyItem([
            'base' => isset($json['base']) ? (int) $json['base'] : null,
            'capacityProvider' => (string) $json['capacityProvider'],
            'weight' => isset($json['weight']) ? (int) $json['weight'] : null,
        ]);
    }

    private function populateResultDeadLetterConfig(array $json): DeadLetterConfig
    {
        return new DeadLetterConfig([
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
        ]);
    }

    private function populateResultEcsParameters(array $json): EcsParameters
    {
        return new EcsParameters([
            'CapacityProviderStrategy' => !isset($json['CapacityProviderStrategy']) ? null : $this->populateResultCapacityProviderStrategy($json['CapacityProviderStrategy']),
            'EnableECSManagedTags' => isset($json['EnableECSManagedTags']) ? filter_var($json['EnableECSManagedTags'], \FILTER_VALIDATE_BOOLEAN) : null,
            'EnableExecuteCommand' => isset($json['EnableExecuteCommand']) ? filter_var($json['EnableExecuteCommand'], \FILTER_VALIDATE_BOOLEAN) : null,
            'Group' => isset($json['Group']) ? (string) $json['Group'] : null,
            'LaunchType' => isset($json['LaunchType']) ? (!LaunchType::exists((string) $json['LaunchType']) ? LaunchType::UNKNOWN_TO_SDK : (string) $json['LaunchType']) : null,
            'NetworkConfiguration' => empty($json['NetworkConfiguration']) ? null : $this->populateResultNetworkConfiguration($json['NetworkConfiguration']),
            'PlacementConstraints' => !isset($json['PlacementConstraints']) ? null : $this->populateResultPlacementConstraints($json['PlacementConstraints']),
            'PlacementStrategy' => !isset($json['PlacementStrategy']) ? null : $this->populateResultPlacementStrategies($json['PlacementStrategy']),
            'PlatformVersion' => isset($json['PlatformVersion']) ? (string) $json['PlatformVersion'] : null,
            'PropagateTags' => isset($json['PropagateTags']) ? (!PropagateTags::exists((string) $json['PropagateTags']) ? PropagateTags::UNKNOWN_TO_SDK : (string) $json['PropagateTags']) : null,
            'ReferenceId' => isset($json['ReferenceId']) ? (string) $json['ReferenceId'] : null,
            'Tags' => !isset($json['Tags']) ? null : $this->populateResultTags($json['Tags']),
            'TaskCount' => isset($json['TaskCount']) ? (int) $json['TaskCount'] : null,
            'TaskDefinitionArn' => (string) $json['TaskDefinitionArn'],
        ]);
    }

    private function populateResultEventBridgeParameters(array $json): EventBridgeParameters
    {
        return new EventBridgeParameters([
            'DetailType' => (string) $json['DetailType'],
            'Source' => (string) $json['Source'],
        ]);
    }

    private function populateResultFlexibleTimeWindow(array $json): FlexibleTimeWindow
    {
        return new FlexibleTimeWindow([
            'MaximumWindowInMinutes' => isset($json['MaximumWindowInMinutes']) ? (int) $json['MaximumWindowInMinutes'] : null,
            'Mode' => !FlexibleTimeWindowMode::exists((string) $json['Mode']) ? FlexibleTimeWindowMode::UNKNOWN_TO_SDK : (string) $json['Mode'],
        ]);
    }

    private function populateResultKinesisParameters(array $json): KinesisParameters
    {
        return new KinesisParameters([
            'PartitionKey' => (string) $json['PartitionKey'],
        ]);
    }

    private function populateResultNetworkConfiguration(array $json): NetworkConfiguration
    {
        return new NetworkConfiguration([
            'awsvpcConfiguration' => empty($json['awsvpcConfiguration']) ? null : $this->populateResultAwsVpcConfiguration($json['awsvpcConfiguration']),
        ]);
    }

    private function populateResultPlacementConstraint(array $json): PlacementConstraint
    {
        return new PlacementConstraint([
            'expression' => isset($json['expression']) ? (string) $json['expression'] : null,
            'type' => isset($json['type']) ? (!PlacementConstraintType::exists((string) $json['type']) ? PlacementConstraintType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
        ]);
    }

    /**
     * @return PlacementConstraint[]
     */
    private function populateResultPlacementConstraints(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPlacementConstraint($item);
        }

        return $items;
    }

    /**
     * @return PlacementStrategy[]
     */
    private function populateResultPlacementStrategies(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPlacementStrategy($item);
        }

        return $items;
    }

    private function populateResultPlacementStrategy(array $json): PlacementStrategy
    {
        return new PlacementStrategy([
            'field' => isset($json['field']) ? (string) $json['field'] : null,
            'type' => isset($json['type']) ? (!PlacementStrategyType::exists((string) $json['type']) ? PlacementStrategyType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
        ]);
    }

    private function populateResultRetryPolicy(array $json): RetryPolicy
    {
        return new RetryPolicy([
            'MaximumEventAgeInSeconds' => isset($json['MaximumEventAgeInSeconds']) ? (int) $json['MaximumEventAgeInSeconds'] : null,
            'MaximumRetryAttempts' => isset($json['MaximumRetryAttempts']) ? (int) $json['MaximumRetryAttempts'] : null,
        ]);
    }

    private function populateResultSageMakerPipelineParameter(array $json): SageMakerPipelineParameter
    {
        return new SageMakerPipelineParameter([
            'Name' => (string) $json['Name'],
            'Value' => (string) $json['Value'],
        ]);
    }

    /**
     * @return SageMakerPipelineParameter[]
     */
    private function populateResultSageMakerPipelineParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSageMakerPipelineParameter($item);
        }

        return $items;
    }

    private function populateResultSageMakerPipelineParameters(array $json): SageMakerPipelineParameters
    {
        return new SageMakerPipelineParameters([
            'PipelineParameterList' => !isset($json['PipelineParameterList']) ? null : $this->populateResultSageMakerPipelineParameterList($json['PipelineParameterList']),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultSecurityGroups(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultSqsParameters(array $json): SqsParameters
    {
        return new SqsParameters([
            'MessageGroupId' => isset($json['MessageGroupId']) ? (string) $json['MessageGroupId'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultSubnets(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return array<string, string>
     */
    private function populateResultTagMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    /**
     * @return array<string, string>[]
     */
    private function populateResultTags(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTagMap($item);
        }

        return $items;
    }

    private function populateResultTarget(array $json): Target
    {
        return new Target([
            'Arn' => (string) $json['Arn'],
            'DeadLetterConfig' => empty($json['DeadLetterConfig']) ? null : $this->populateResultDeadLetterConfig($json['DeadLetterConfig']),
            'EcsParameters' => empty($json['EcsParameters']) ? null : $this->populateResultEcsParameters($json['EcsParameters']),
            'EventBridgeParameters' => empty($json['EventBridgeParameters']) ? null : $this->populateResultEventBridgeParameters($json['EventBridgeParameters']),
            'Input' => isset($json['Input']) ? (string) $json['Input'] : null,
            'KinesisParameters' => empty($json['KinesisParameters']) ? null : $this->populateResultKinesisParameters($json['KinesisParameters']),
            'RetryPolicy' => empty($json['RetryPolicy']) ? null : $this->populateResultRetryPolicy($json['RetryPolicy']),
            'RoleArn' => (string) $json['RoleArn'],
            'SageMakerPipelineParameters' => empty($json['SageMakerPipelineParameters']) ? null : $this->populateResultSageMakerPipelineParameters($json['SageMakerPipelineParameters']),
            'SqsParameters' => empty($json['SqsParameters']) ? null : $this->populateResultSqsParameters($json['SqsParameters']),
        ]);
    }
}
