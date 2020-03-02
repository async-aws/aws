<?php

namespace AsyncAws\CloudFormation\Result;

class RollbackTrigger
{
    private $Arn;

    private $Type;

    /**
     * @param array{
     *   Arn: string,
     *   Type: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Arn = $input['Arn'];
        $this->Type = $input['Type'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * The Amazon Resource Name (ARN) of the rollback trigger.
     */
    public function getArn(): string
    {
        return $this->Arn;
    }

    /**
     * The resource type of the rollback trigger. Currently, AWS::CloudWatch::Alarm is the only supported resource type.
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-properties-cw-alarm.html
     */
    public function getType(): string
    {
        return $this->Type;
    }
}
