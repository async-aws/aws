<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Enum\TransitionDefaultMinimumObjectSize;
use AsyncAws\S3\Result\PutBucketLifecycleConfigurationOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutBucketLifecycleConfigurationOutputTest extends TestCase
{
    public function testPutBucketLifecycleConfigurationOutput(): void
    {
        $response = new SimpleMockedResponse('', [
            'x-amz-transition-default-minimum-object-size' => TransitionDefaultMinimumObjectSize::ALL_STORAGE_CLASSES_128K,
        ]);

        $client = new MockHttpClient($response);
        $result = new PutBucketLifecycleConfigurationOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(TransitionDefaultMinimumObjectSize::ALL_STORAGE_CLASSES_128K, $result->getTransitionDefaultMinimumObjectSize());
    }
}
