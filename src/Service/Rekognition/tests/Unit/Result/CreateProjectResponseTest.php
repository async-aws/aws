<?php

namespace AsyncAws\Rekognition\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CreateProjectRequest;
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Result\CreateProjectResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateProjectResponseTest extends TestCase
{
    public function testCreateProjectResponse(): void
    {
        self::fail('Not implemented');

                        // see https://docs.aws.amazon.com/rekognition/latest/APIReference/API_CreateProject.html
                        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

                        $client = new MockHttpClient($response);
                        $result = new CreateProjectResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

                        self::assertSame("changeIt", $result->getProjectArn());
    }
}
