<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\DescribeStackEventsOutput;
use AsyncAws\CloudFormation\Result\StackEvent;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStackEventsOutputTest extends TestCase
{
    public function testDescribeStackEventsOutput(): void
    {
        $response = new SimpleMockedResponse('<DescribeStackEventsResponse xmlns="http://cloudformation.amazonaws.com/doc/2010-05-15/">
  <DescribeStackEventsResult>
    <StackEvents>
      <member>
        <EventId>8aa00990-8cde-43d0-8013-20a5e4584626</EventId>
        <PhysicalResourceId>arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</PhysicalResourceId>
        <ResourceStatus>UPDATE_COMPLETE</ResourceStatus>
        <StackId>arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</StackId>
        <StackName>sfhackday-dev</StackName>
        <LogicalResourceId>sfhackday-dev</LogicalResourceId>
        <Timestamp>2019-09-23T21:05:09.542Z</Timestamp>
        <ResourceType>AWS::CloudFormation::Stack</ResourceType>
      </member>
      <member>
        <EventId>22c2770d-d86a-4366-ae96-21b9dc21606c</EventId>
        <PhysicalResourceId>arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</PhysicalResourceId>
        <ResourceStatus>UPDATE_COMPLETE_CLEANUP_IN_PROGRESS</ResourceStatus>
        <StackId>arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</StackId>
        <StackName>sfhackday-dev</StackName>
        <LogicalResourceId>sfhackday-dev</LogicalResourceId>
        <Timestamp>2019-09-23T21:05:08.979Z</Timestamp>
        <ResourceType>AWS::CloudFormation::Stack</ResourceType>
      </member>
      <member>
        <EventId>eccbf097-933d-41b7-a3ed-2921340f033b</EventId>
        <PhysicalResourceId>arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</PhysicalResourceId>
        <ResourceStatus>CREATE_IN_PROGRESS</ResourceStatus>
        <ResourceStatusReason>User Initiated</ResourceStatusReason>
        <StackId>arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</StackId>
        <StackName>sfhackday-dev</StackName>
        <LogicalResourceId>sfhackday-dev</LogicalResourceId>
        <Timestamp>2019-09-23T21:03:09.483Z</Timestamp>
        <ResourceType>AWS::CloudFormation::Stack</ResourceType>
      </member>
    </StackEvents>
  </DescribeStackEventsResult>
  <ResponseMetadata>
    <RequestId>0b836460-dcb3-4e4d-8561-18a3c39bb5aa</RequestId>
  </ResponseMetadata>
</DescribeStackEventsResponse>
');

        $client = new MockHttpClient($response);
        $result = new DescribeStackEventsOutput($client->request('POST', 'http://localhost'), $client);

        /** @var StackEvent[] $stackEvents */
        $stackEvents = [];
        foreach ($result->getStackEvents(true) as $s) {
            $stackEvents[] = $s;
        }

        self::assertCount(3, $stackEvents);
        self::assertInstanceOf(StackEvent::class, $stackEvents[0]);
        self::assertEquals('8aa00990-8cde-43d0-8013-20a5e4584626', $stackEvents[0]->getEventId());
        self::assertEquals('arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85', $stackEvents[0]->getPhysicalResourceId());
        self::assertEquals('UPDATE_COMPLETE', $stackEvents[0]->getResourceStatus());
        self::assertEquals('arn:aws:cloudformation:eu-west-1:32456442:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85', $stackEvents[0]->getStackId());
        self::assertEquals('sfhackday-dev', $stackEvents[0]->getStackName());
        self::assertEquals('sfhackday-dev', $stackEvents[0]->getLogicalResourceId());
        self::assertEquals('AWS::CloudFormation::Stack', $stackEvents[0]->getResourceType());

        self::assertNull($stackEvents[0]->getClientRequestToken());
        self::assertNull($stackEvents[0]->getResourceProperties());
        self::assertNull($stackEvents[0]->getResourceStatusReason());
    }
}
