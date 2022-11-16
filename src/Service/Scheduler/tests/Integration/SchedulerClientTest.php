<?php

namespace AsyncAws\Scheduler\Tests\Integration;

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
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\DeadLetterConfig;
use AsyncAws\Scheduler\ValueObject\FlexibleTimeWindow;
use AsyncAws\Scheduler\ValueObject\RetryPolicy;
use AsyncAws\Scheduler\ValueObject\Tag;
use AsyncAws\Scheduler\ValueObject\Target;

class SchedulerClientTest extends TestCase
{
    public function testCreateScheduleGroup(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

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

        $result->resolve();

        // assertTODO
    }

    public function testCreateSchedule(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

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

        $result->resolve();

        // assertTODO
    }

    public function testDeleteScheduleGroup(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

        $input = new DeleteScheduleGroupInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'Name' => 'foo',
        ]);
        $result = $client->deleteScheduleGroup($input);

        $result->resolve();

        // assertTODO
    }

    public function testDeleteSchedule(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

        $input = new DeleteScheduleInput([
            'ClientToken' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'GroupName' => 'foo',
            'Name' => 'bar',
        ]);
        $result = $client->deleteSchedule($input);

        $result->resolve();

        // assertTODO
    }

    public function testGetScheduleGroup(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

        $input = new GetScheduleGroupInput([
            'Name' => 'foo',
        ]);
        $result = $client->getScheduleGroup($input);

        $result->resolve();

        // assertTODO
    }

    public function testGetSchedule(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

        $input = new GetScheduleInput([
            'GroupName' => 'foo',
            'Name' => 'bar',
        ]);
        $result = $client->getSchedule($input);

        $result->resolve();

        // assertTODO
    }

    public function testListScheduleGroups(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

        $input = new ListScheduleGroupsInput([
            'MaxResults' => 20,
            'NamePrefix' => 'foo',
        ]);
        $result = $client->listScheduleGroups($input);

        $result->resolve();

        // assertTODO
    }

    public function testListSchedules(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

        $input = new ListSchedulesInput([
            'GroupName' => 'foo',
            'MaxResults' => 20,
            'NamePrefix' => 'bar',
            'State' => ScheduleState::ENABLED,
        ]);
        $result = $client->listSchedules($input);

        $result->resolve();

        // assertTODO
    }

    public function testUpdateSchedule(): void
    {
        self::markTestIncomplete('Cannot test without support for scheduler.');

        $client = $this->getClient();

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

        $result->resolve();

        // assertTODO
    }

    private function getClient(): SchedulerClient
    {
        self::fail('Not implemented');

        return new SchedulerClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
