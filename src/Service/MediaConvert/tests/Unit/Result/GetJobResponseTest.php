<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Enum\JobStatus;
use AsyncAws\MediaConvert\Result\GetJobResponse;
use AsyncAws\MediaConvert\ValueObject\Job;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetJobResponseTest extends TestCase
{
    public function testGetJobResponse(): void
    {
        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_GetJob.html
        $response = new SimpleMockedResponse('{
            "job": {
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
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetJobResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $job = $result->getJob();

        self::assertInstanceOf(Job::class, $job);
        self::assertSame('ZJ1648461', $job->getId());
        self::assertSame(JobStatus::COMPLETE, $job->getStatus());
        self::assertEquals(new \DateTimeImmutable('2023-05-30T13:19:27Z'), $job->getCreatedAt());
        self::assertSame(['usage' => 'test'], $job->getUserMetadata());
        self::assertSame('MediaConvert_Default_Role', $job->getRole());
        self::assertCount(1, $job->getSettings()->getInputs());
        self::assertSame('s3://examplebucket/file.mp4', $job->getSettings()->getInputs()[0]->getFileInput());
    }
}
