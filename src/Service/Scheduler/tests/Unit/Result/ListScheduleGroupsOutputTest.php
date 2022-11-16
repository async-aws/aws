<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\ScheduleGroupState;
use AsyncAws\Scheduler\Input\ListScheduleGroupsInput;
use AsyncAws\Scheduler\Result\ListScheduleGroupsOutput;
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\ScheduleGroupSummary;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListScheduleGroupsOutputTest extends TestCase
{
    public function testListScheduleGroupsOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_ListScheduleGroups.html
        $response = new SimpleMockedResponse('{
            "NextToken": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
            "ScheduleGroups": [
                {
                    "Arn": "arn:aws:scheduler:us-east-1:111111111111:schedule-group/foo",
                    "CreationDate": 1669852800,
                    "LastModificationDate": 1672531200,
                    "Name": "foo",
                    "State": "ACTIVE"
                },
                {
                    "Arn": "arn:aws:scheduler:us-east-1:111111111111:schedule-group/bar",
                    "CreationDate": 1669852800,
                    "LastModificationDate": 1672531200,
                    "Name": "bar",
                    "State": "DELETING"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListScheduleGroupsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SchedulerClient(), new ListScheduleGroupsInput([]));

        self::assertFalse($result->info()['resolved']);
        self::assertSame('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $result->getNextToken());

        $scheduleGroups = iterator_to_array($result->getScheduleGroups(true));
        self::assertCount(2, $scheduleGroups);

        self::assertInstanceOf(ScheduleGroupSummary::class, $scheduleGroups[0]);
        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule-group/foo', $scheduleGroups[0]->getArn());
        self::assertSame(1669852800, $scheduleGroups[0]->getCreationDate()->getTimestamp());
        self::assertSame(1672531200, $scheduleGroups[0]->getLastModificationDate()->getTimestamp());
        self::assertSame('foo', $scheduleGroups[0]->getName());
        self::assertSame(ScheduleGroupState::ACTIVE, $scheduleGroups[0]->getState());

        self::assertInstanceOf(ScheduleGroupSummary::class, $scheduleGroups[1]);
        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule-group/bar', $scheduleGroups[1]->getArn());
        self::assertSame(1669852800, $scheduleGroups[1]->getCreationDate()->getTimestamp());
        self::assertSame(1672531200, $scheduleGroups[1]->getLastModificationDate()->getTimestamp());
        self::assertSame('bar', $scheduleGroups[1]->getName());
        self::assertSame(ScheduleGroupState::DELETING, $scheduleGroups[1]->getState());
    }
}
