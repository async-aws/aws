<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Input\ListJobsRequest;
use AsyncAws\MediaConvert\MediaConvertClient;
use AsyncAws\MediaConvert\Result\ListJobsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListJobsResponseTest extends TestCase
{
    public function testListJobsResponse(): void
    {
        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_ListJobs.html
        $response = new SimpleMockedResponse('{
            "jobs": [
                {
                    "id": "ZJ1648461",
                    "createdAt": "1685452767.000",
                    "role": "MediaConvert_Default_Role",
                    "status": "COMPLETE",
                    "settings": {
                        "inputs": [
                            {
                                "fileInput": "s3://examplebucket/file.mp4"
                            }
                        ]
                    },
                    "userMetadata": {
                        "usage": "test"
                    }
                },
                {
                    "id": "ZJ1648251",
                    "createdAt": "1685452712.000",
                    "role": "MediaConvert_Default_Role",
                    "status": "PROGRESSING",
                    "settings": {
                        "inputs": [
                            {
                                "fileInput": "s3://examplebucket/file.mov"
                            }
                        ]
                    },
                    "userMetadata": {
                        "usage": "test"
                    }
                }
            ],
            "nextToken": "token"
        }');

        $client = new MockHttpClient($response);
        $result = new ListJobsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new MediaConvertClient(), new ListJobsRequest([]));

        self::assertCount(2, iterator_to_array($result->getJobs(true)));
        self::assertSame('token', $result->getNextToken());
    }
}
