<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\ListSchedulesInput;
use AsyncAws\Scheduler\Result\ListSchedulesOutput;
use AsyncAws\Scheduler\SchedulerClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListSchedulesOutputTest extends TestCase
{
    public function testListSchedulesOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_ListSchedules.html
        $response = new SimpleMockedResponse('{
            "NextToken": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
            "Schedules": [
                {
                    "Arn": "arn:aws:scheduler:us-east-1:111111111111:schedule/bar",
                    "CreationDate": 1669852800,
                    "GroupName": "foo",
                    "LastModificationDate": 1672531200,
                    "Name": "bar",
                    "State": "ENABLED",
                    "Target": { 
                        "Arn": "arn:aws:sqs:us-east-1:111111111111:example"
                    }
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListSchedulesOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SchedulerClient(), new ListSchedulesInput([]));

        self::assertFalse($result->info()['resolved']);
        self::assertSame('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $result->getNextToken());

        $schedules = iterator_to_array($result->getSchedules(true));
        self::assertCount(1, $schedules);

        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule/bar', $schedules[0]->getArn());
        self::assertSame(1669852800, $schedules[0]->getCreationDate()->getTimestamp());
        self::assertSame('foo', $schedules[0]->getGroupName());
        self::assertSame(1672531200, $schedules[0]->getLastModificationDate()->getTimestamp());
        self::assertSame('bar', $schedules[0]->getName());
        self::assertSame(ScheduleState::ENABLED, $schedules[0]->getState());
        self::assertSame('arn:aws:sqs:us-east-1:111111111111:example', $schedules[0]->getTarget()->getArn());
    }
}
