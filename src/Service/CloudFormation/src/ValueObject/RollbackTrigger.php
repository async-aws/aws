<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A rollback trigger CloudFormation monitors during creation and updating of stacks. If any of the alarms you specify
 * goes to ALARM state during the stack operation or within the specified monitoring period afterwards, CloudFormation
 * rolls back the entire stack operation.
 */
final class RollbackTrigger
{
    /**
     * The Amazon Resource Name (ARN) of the rollback trigger.
     *
     * If a specified trigger is missing, the entire stack operation fails and is rolled back.
     *
     * @var string
     */
    private $arn;

    /**
     * The resource type of the rollback trigger. Specify either AWS::CloudWatch::Alarm [^1] or
     * AWS::CloudWatch::CompositeAlarm [^2] resource types.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/TemplateReference/aws-resource-cloudwatch-alarm.html
     * [^2]: https://docs.aws.amazon.com/AWSCloudFormation/latest/TemplateReference/aws-resource-cloudwatch-compositealarm.html
     *
     * @var string
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
        $this->arn = $input['Arn'] ?? $this->throwException(new InvalidArgument('Missing required field "Arn".'));
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
    }

    /**
     * @param array{
     *   Arn: string,
     *   Type: string,
     * }|RollbackTrigger $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
