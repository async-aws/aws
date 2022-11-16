<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Result\CreateScheduleGroupOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateScheduleGroupOutputTest extends TestCase
{
    public function testCreateScheduleGroupOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_CreateScheduleGroup.html
        $response = new SimpleMockedResponse('{
            "ScheduleGroupArn": "arn:aws:scheduler:us-east-1:111111111111:schedule-group/foo"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateScheduleGroupOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule-group/foo', $result->getScheduleGroupArn());
    }
}
