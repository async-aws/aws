<?php

namespace AsyncAws\MediaConvert\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Input\CancelJobRequest;
use AsyncAws\MediaConvert\Input\CreateJobRequest;
use AsyncAws\MediaConvert\Input\DescribeEndpointsRequest;
use AsyncAws\MediaConvert\Input\GetJobRequest;
use AsyncAws\MediaConvert\Input\ListJobsRequest;
use AsyncAws\MediaConvert\MediaConvertClient;
use AsyncAws\MediaConvert\Result\CancelJobResponse;
use AsyncAws\MediaConvert\Result\CreateJobResponse;
use AsyncAws\MediaConvert\Result\DescribeEndpointsResponse;
use AsyncAws\MediaConvert\Result\GetJobResponse;
use AsyncAws\MediaConvert\Result\ListJobsResponse;
use AsyncAws\MediaConvert\ValueObject\JobSettings;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class MediaConvertClientTest extends TestCase
{
    public function testCancelJob(): void
    {
        $client = new MediaConvertClient([], new NullProvider(), new MockHttpClient([
            new MockResponse('{"endpoints": [{"url":"http://account.localhost"}]}'),
            new MockResponse(),
        ]));

        $input = new CancelJobRequest([
            'Id' => 'change me',
        ]);
        $result = $client->cancelJob($input);

        self::assertInstanceOf(CancelJobResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateJob(): void
    {
        $client = new MediaConvertClient([], new NullProvider(), new MockHttpClient([
            new MockResponse('{"endpoints": [{"url":"http://account.localhost"}]}'),
            new MockResponse(),
        ]));

        $input = new CreateJobRequest([
            'Role' => 'change me',
            'Settings' => new JobSettings([
            ]),
        ]);
        $result = $client->createJob($input);

        self::assertInstanceOf(CreateJobResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    #[IgnoreDeprecations]
    public function testDescribeEndpoints(): void
    {
        $client = new MediaConvertClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeEndpointsRequest([
        ]);
        $result = $client->describeEndpoints($input);

        self::assertInstanceOf(DescribeEndpointsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetJob(): void
    {
        $client = new MediaConvertClient([], new NullProvider(), new MockHttpClient([
            new MockResponse('{"endpoints": [{"url":"http://account.localhost"}]}'),
            new MockResponse(),
        ]));

        $input = new GetJobRequest([
            'Id' => 'ZJ1648461e',
        ]);
        $result = $client->getJob($input);

        self::assertInstanceOf(GetJobResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListJobs(): void
    {
        $client = new MediaConvertClient([], new NullProvider(), new MockHttpClient([
            new MockResponse('{"endpoints": [{"url":"http://account.localhost"}]}'),
            new MockResponse(),
        ]));

        $input = new ListJobsRequest([
        ]);
        $result = $client->listJobs($input);

        self::assertInstanceOf(ListJobsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
