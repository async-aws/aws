<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * A rollback trigger CloudFormation monitors during creation and updating of stacks. If any of the alarms you specify
 * goes to ALARM state during the stack operation or within the specified monitoring period afterwards, CloudFormation
 * rolls back the entire stack operation.
 */
final class RollbackTrigger
{
    /**
     * The Amazon Resource Name (ARN) of the rollback trigger.
     */
    private $arn;

    /**
     * The resource type of the rollback trigger. Specify either AWS::CloudWatch::Alarm or AWS::CloudWatch::CompositeAlarm
     * resource types.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-properties-cw-alarm.html
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-resource-cloudwatch-compositealarm.html
     */
    private $type;

    /**
     * @param array{
     *   Arn: string,
     *   Type: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->type = $input['Type'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): string
    {
        return $this->arn;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
