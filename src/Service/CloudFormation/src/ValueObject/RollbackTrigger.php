<?php

namespace AsyncAws\CloudFormation\ValueObject;

final class RollbackTrigger
{
    /**
     * The Amazon Resource Name (ARN) of the rollback trigger.
     */
    private $Arn;

    /**
     * The resource type of the rollback trigger. Currently, AWS::CloudWatch::Alarm is the only supported resource type.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-properties-cw-alarm.html
     */
    private $Type;

    /**
     * @param array{
     *   Arn: string,
     *   Type: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Arn = $input['Arn'] ?? null;
        $this->Type = $input['Type'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): string
    {
        return $this->Arn;
    }

    public function getType(): string
    {
        return $this->Type;
    }
}
