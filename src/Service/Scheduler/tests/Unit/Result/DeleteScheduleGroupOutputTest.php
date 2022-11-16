<?php

namespace AsyncAws\Scheduler\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Scheduler\Result\DeleteScheduleGroupOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteScheduleGroupOutputTest extends TestCase
{
    public function testDeleteScheduleGroupOutput(): void
    {
        // see https://docs.aws.amazon.com/scheduler/latest/APIReference/API_DeleteScheduleGroup.html
        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new DeleteScheduleGroupOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
    }
}
