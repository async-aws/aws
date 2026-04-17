<?php

namespace AsyncAws\Ec2\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ec2\Result\DeregisterImageResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeregisterImageResultTest extends TestCase
{
    public function testDeregisterImageResult(): void
    {
        // see https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DeregisterImage.html
        $response = new SimpleMockedResponse('<DeregisterImageResponse xmlns="http://ec2.amazonaws.com/doc/2016-11-15/">
    <requestId>3be1508e-c444-4fef-89cc-0b1223c4f02fEXAMPLE</requestId>
    <return>true</return>
</DeregisterImageResponse>');

        $client = new MockHttpClient($response);
        $result = new DeregisterImageResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertTrue($result->getReturn());
        self::assertSame([], $result->getDeleteSnapshotResults());
    }
}
