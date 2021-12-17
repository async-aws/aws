<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\DescribeStackDriftDetectionStatusOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStackDriftDetectionStatusOutputTest extends TestCase
{
    public function testDescribeStackDriftDetectionStatusOutput(): void
    {
        $response = new SimpleMockedResponse('<DescribeStackDriftDetectionStatusResponse xmlns="http://cloudformation.amazonaws.com/doc/2010-05-15/">
  <DescribeStackDriftDetectionStatusResult>
    <DetectionStatus>DETECTION_COMPLETE</DetectionStatus>
    <StackDriftDetectionId>b78ac9b0-dec1-11e7-a451-503a3example</StackDriftDetectionId>
    <DriftedStackResourceCount>0</DriftedStackResourceCount>
    <StackId>arn:aws:cloudformation:us-east-1:012345678910:stack/example/cb438120-6cc7-11e7-998e-50example</StackId>
    <StackDriftStatus>IN_SYNC</StackDriftStatus>
    <Timestamp>2017-12-11T22:22:04.747Z</Timestamp>
  </DescribeStackDriftDetectionStatusResult>
  <ResponseMetadata>
    <RequestId>f89bbda1-dec1-11e7-83c6-d92bexample</RequestId>
  </ResponseMetadata>
</DescribeStackDriftDetectionStatusResponse>');

        $client = new MockHttpClient($response);
        $result = new DescribeStackDriftDetectionStatusOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:cloudformation:us-east-1:012345678910:stack/example/cb438120-6cc7-11e7-998e-50example', $result->getStackId());
        self::assertSame('b78ac9b0-dec1-11e7-a451-503a3example', $result->getStackDriftDetectionId());
        self::assertSame('IN_SYNC', $result->getStackDriftStatus());
        self::assertSame('DETECTION_COMPLETE', $result->getDetectionStatus());
        self::assertNull($result->getDetectionStatusReason());
        self::assertSame(0, $result->getDriftedStackResourceCount());
    }
}
