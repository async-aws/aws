<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Result\UpdateScheduleOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateScheduleOutputTest extends TestCase
{
    public function testUpdateScheduleOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_UpdateSchedule.html
        $response = new SimpleMockedResponse('{
            "ScheduleArn": "arn:aws:scheduler:us-east-1:111111111111:schedule/bar"
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateScheduleOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
        self::assertSame('arn:aws:scheduler:us-east-1:111111111111:schedule/bar', $result->getScheduleArn());
    }
}
