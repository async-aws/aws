<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Enum\ProjectStatus;
use AsyncAws\Rekognition\Result\DeleteProjectResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteProjectResponseTest extends TestCase
{
    public function testDeleteProjectResponse(): void
    {
        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteProject.html
        $response = new SimpleMockedResponse('{
           "Status": "CREATING"
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteProjectResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(ProjectStatus::CREATING, $result->getStatus());
    }
}
