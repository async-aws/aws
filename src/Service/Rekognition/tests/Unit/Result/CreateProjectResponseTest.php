<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Result\CreateProjectResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateProjectResponseTest extends TestCase
{
    public function testCreateProjectResponse(): void
    {
        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateProject.html
        $response = new SimpleMockedResponse('{
            "ProjectArn": "aws:rekognition:us-west-2:123456789012:project\\/demo"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateProjectResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('aws:rekognition:us-west-2:123456789012:project/demo', $result->getProjectArn());
    }
}
