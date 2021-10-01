<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Enum\SchemaStatus;
use AsyncAws\AppSync\Result\GetSchemaCreationStatusResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetSchemaCreationStatusResponseTest extends TestCase
{
    public function testGetSchemaCreationStatusResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_GetSchemaCreationStatus.html
        $response = new SimpleMockedResponse('{
           "details": "Detailed information",
           "status": "SUCCESS"
        }');

        $client = new MockHttpClient($response);
        $result = new GetSchemaCreationStatusResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(SchemaStatus::SUCCESS, $result->getstatus());
        self::assertSame('Detailed information', $result->getdetails());
    }
}
