<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Result\DeleteScheduleOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteScheduleOutputTest extends TestCase
{
    public function testDeleteScheduleOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_DeleteSchedule.html
        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new DeleteScheduleOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
    }
}
