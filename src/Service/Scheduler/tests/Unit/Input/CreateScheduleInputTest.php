<?php

namespace AsyncAws\Scheduler\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\CreateScheduleInput;
use AsyncAws\Scheduler\ValueObject\DeadLetterConfig;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\RetryPolicy;
use AsyncAws\Scheduler\ValueObject\Target;

class CreateScheduleInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateScheduleInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'Description' => 'Bar Schedule',
            'FlexibleTimeWindow' => new FlexibleTimeWindow([
                'Mode' => FlexibleTimeWindowMode::OFF,
            ]),
            'GroupName' => 'foo',
            'Name' => 'bar',
            'ScheduleExpression' => 'at(2023-01-01T00:00:00)',
            'ScheduleExpressionTimezone' => 'UTC',
            'State' => ScheduleState::ENABLED,
            'Target' => new Target([
                'Arn' => 'arn:aws:sqs:us-east-1:111111111111:example',
                'DeadLetterConfig' => new DeadLetterConfig([
                    'Arn' => 'arn:aws:sqs:us-east-1:111111111111:dlq',
                ]),
                'Input' => 'payload',
                'RetryPolicy' => new RetryPolicy([
                    'MaximumEventAgeInSeconds' => 86400,
                    'MaximumRetryAttempts' => 185,
                ]),
                'RoleArn' => 'arn:aws:iam::111111111111:role/scheduler-sqs-role',
            ]),
        ]);

        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_CreateSchedule.html
        $expected = '
            POST /schedules/bar HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
                "ClientToken": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "Description": "Bar Schedule",
                "FlexibleTimeWindow": {
                    "Mode": "OFF"
                },
                "GroupName": "foo",
                "ScheduleExpression": "at(2023-01-01T00:00:00)",
                "ScheduleExpressionTimezone": "UTC",
                "State": "ENABLED",
                "Target": {
                    "Arn": "arn:aws:sqs:us-east-1:111111111111:example",
                    "DeadLetterConfig": {
                        "Arn": "arn:aws:sqs:us-east-1:111111111111:dlq"
                    },
                    "Input": "payload",
                    "RetryPolicy": {
                        "MaximumEventAgeInSeconds": 86400,
                        "MaximumRetryAttempts": 185
                    },
                    "RoleArn": "arn:aws:iam::111111111111:role/scheduler-sqs-role"
                }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
