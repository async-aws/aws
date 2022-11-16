<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Enum\ScheduleGroupState;
use AsyncAws\Scheduler\Result\GetScheduleGroupOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetScheduleGroupOutputTest extends TestCase
{
    public function testGetScheduleGroupOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_GetScheduleGroup.html
        $response = new SimpleMockedResponse('{
            "Arn": "arn:aws:scheduler:us-east-1:111111111111:schedule-group/foo",
            "CreationDate": 1669852800,
            "LastModificationDate": 1672531200,
            "Name": "foo",
            "State": "ACTIVE"
        }');

        $client = new MockHttpClient($response);
        $result = new GetScheduleGroupOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule-group/foo', $result->getArn());
        self::assertSame(1669852800, $result->getCreationDate()->getTimestamp());
        self::assertSame(1672531200, $result->getLastModificationDate()->getTimestamp());
        self::assertSame('foo', $result->getName());
        self::assertSame(ScheduleGroupState::ACTIVE, $result->getState());
    }
}
