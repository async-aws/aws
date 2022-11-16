<?php

namespace AsyncAws\Scheduler\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\CreateScheduleGroupInput;
use AsyncAws\Scheduler\Input\CreateScheduleInput;
use AsyncAws\Scheduler\Input\DeleteScheduleGroupInput;
use AsyncAws\Scheduler\Input\DeleteScheduleInput;
use AsyncAws\Scheduler\Input\GetScheduleGroupInput;
use AsyncAws\Scheduler\Input\GetScheduleInput;
use AsyncAws\Scheduler\Input\ListScheduleGroupsInput;
use AsyncAws\Scheduler\Input\ListSchedulesInput;
use AsyncAws\Scheduler\Input\UpdateScheduleInput;
use AsyncAws\Scheduler\Result\CreateScheduleGroupOutput;
use AsyncAws\Scheduler\Result\CreateScheduleOutput;
use AsyncAws\Scheduler\Result\DeleteScheduleGroupOutput;
use AsyncAws\Scheduler\Result\DeleteScheduleOutput;
use AsyncAws\Scheduler\Result\GetScheduleGroupOutput;
use AsyncAws\Scheduler\Result\GetScheduleOutput;
use AsyncAws\Scheduler\Result\ListScheduleGroupsOutput;
use AsyncAws\Scheduler\Result\ListSchedulesOutput;
use AsyncAws\Scheduler\Result\UpdateScheduleOutput;
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\DeadLetterConfig;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\RetryPolicy;
use AsyncAws\Scheduler\ValueObject\Tag;
use AsyncAws\Scheduler\ValueObject\Target;
use Symfony\Component\HttpClient\MockHttpClient;

class SchedulerClientTest extends TestCase
{
    public function testCreateScheduleGroup(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateScheduleGroupInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'Name' => 'foo',
            'Tags' => [
                new Tag([
                    'Key' => 'bar',
                    'Value' => 'baz',
                ]),
            ],
        ]);
        $result = $client->createScheduleGroup($input);

        self::assertInstanceOf(CreateScheduleGroupOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateSchedule(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

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
        $result = $client->createSchedule($input);

        self::assertInstanceOf(CreateScheduleOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteScheduleGroup(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteScheduleGroupInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'Name' => 'foo',
        ]);
        $result = $client->deleteScheduleGroup($input);

        self::assertInstanceOf(DeleteScheduleGroupOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteSchedule(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteScheduleInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'GroupName' => 'foo',
            'Name' => 'bar',
        ]);
        $result = $client->deleteSchedule($input);

        self::assertInstanceOf(DeleteScheduleOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetScheduleGroup(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new GetScheduleGroupInput([
            'Name' => 'foo',
        ]);
        $result = $client->getScheduleGroup($input);

        self::assertInstanceOf(GetScheduleGroupOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetSchedule(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new GetScheduleInput([
            'GroupName' => 'foo',
            'Name' => 'bar',
        ]);
        $result = $client->getSchedule($input);

        self::assertInstanceOf(GetScheduleOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListScheduleGroups(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new ListScheduleGroupsInput([
            'MaxResults' => 20,
            'NamePrefix' => 'foo',
        ]);
        $result = $client->listScheduleGroups($input);

        self::assertInstanceOf(ListScheduleGroupsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListSchedules(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new ListSchedulesInput([
            'GroupName' => 'foo',
            'MaxResults' => 20,
            'NamePrefix' => 'bar',
            'State' => ScheduleState::ENABLED,
        ]);
        $result = $client->listSchedules($input);

        self::assertInstanceOf(ListSchedulesOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateSchedule(): void
    {
        $client = new SchedulerClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateScheduleInput([
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
        $result = $client->updateSchedule($input);

        self::assertInstanceOf(UpdateScheduleOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
