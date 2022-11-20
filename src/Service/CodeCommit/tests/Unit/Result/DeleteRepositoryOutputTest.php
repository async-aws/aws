<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\DeleteRepositoryOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteRepositoryOutputTest extends TestCase
{
    public function testDeleteRepositoryOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_DeleteRepository.html
        $response = new SimpleMockedResponse('{
            "repositoryId": "f7579e13-b83e-4027-aaef-650c0EXAMPLE"
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteRepositoryOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('f7579e13-b83e-4027-aaef-650c0EXAMPLE', $result->getRepositoryId());
    }
}
