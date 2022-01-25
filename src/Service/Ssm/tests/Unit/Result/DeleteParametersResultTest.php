<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\DeleteParametersResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteParametersResultTest extends TestCase
{
    public function testDeleteParametersResult(): void
    {
        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameters.html
        $response = new SimpleMockedResponse('{
           "DeletedParameters": ["DB_HOST"],
           "InvalidParameters": ["DB_USER"]
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteParametersResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(['DB_HOST'], $result->getDeletedParameters());
        self::assertEquals(['DB_USER'], $result->getInvalidParameters());
    }
}
