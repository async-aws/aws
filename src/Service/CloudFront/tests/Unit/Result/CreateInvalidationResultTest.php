<?php

namespace AsyncAws\CloudFront\Tests\Unit\Result;

use AsyncAws\CloudFront\CloudFrontClient;
use AsyncAws\CloudFront\Input\CreateInvalidationRequest;
use AsyncAws\CloudFront\Result\CreateInvalidationResult;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateInvalidationResultTest extends TestCase
{
    public function testCreateInvalidationResult(): void
    {
        self::fail('Not implemented');

                        // see https://docs.aws.amazon.com/cloudfront/latest/APIReference/API_CreateInvalidation2019_03_26.html
                        $response = new SimpleMockedResponse('<change>it</change>');

                        $client = new MockHttpClient($response);
                        $result = new CreateInvalidationResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

                        self::assertSame("changeIt", $result->getLocation());
        // self::assertTODO(expected, $result->getInvalidation());
    }
}
