<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Result\GetScheduleOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetScheduleOutputTest extends TestCase
{
    public function testGetScheduleOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_GetSchedule.html
        $response = new SimpleMockedResponse('{
            "Arn": "arn:aws:scheduler:us-east-1:111111111111:schedule/bar",
            "CreationDate": 1669852800,
            "Description": "Bar Schedule",
            "FlexibleTimeWindow": {
                "Mode": "OFF"
            },
            "GroupName": "foo",
            "LastModificationDate": 1672531200,
            "Name": "bar",
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
        }');

        $client = new MockHttpClient($response);
        $result = new GetScheduleOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule/bar', $result->getArn());
        self::assertSame(1669852800, $result->getCreationDate()->getTimestamp());
        self::assertSame('Bar Schedule', $result->getDescription());
        self::assertNull($result->getEndDate());
        self::assertSame(FlexibleTimeWindowMode::OFF, $result->getFlexibleTimeWindow()->getMode());
        self::assertSame('foo', $result->getGroupName());
        self::assertNull($result->getKmsKeyArn());
        self::assertSame(1672531200, $result->getLastModificationDate()->getTimestamp());
        self::assertSame('bar', $result->getName());
        self::assertSame('at(2023-01-01T00:00:00)', $result->getScheduleExpression());
        self::assertSame('UTC', $result->getScheduleExpressionTimezone());
        self::assertNull($result->getStartDate());
        self::assertSame(ScheduleState::ENABLED, $result->getState());
        self::assertSame('arn:aws:sqs:us-east-1:111111111111:example', $result->getTarget()->getArn());
        self::assertSame('arn:aws:sqs:us-east-1:111111111111:dlq', $result->getTarget()->getDeadLetterConfig()->getArn());
        self::assertSame('payload', $result->getTarget()->getInput());
        self::assertSame(86400, $result->getTarget()->getRetryPolicy()->getMaximumEventAgeInSeconds());
        self::assertSame(185, $result->getTarget()->getRetryPolicy()->getMaximumRetryAttempts());
        self::assertSame('arn:aws:iam::111111111111:role/scheduler-sqs-role', $result->getTarget()->getRoleArn());
    }
}
