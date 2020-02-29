<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\CloudFormation\Result\RollbackConfiguration;
use AsyncAws\CloudFormation\Result\Stack;
use AsyncAws\CloudFormation\Result\StackDriftInformation;
use AsyncAws\CloudFormation\Result\Tag;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStacksOutputTest extends TestCase
{
    public function testDescribeStacksOutput()
    {
        $xml = <<<XML
<DescribeStacksResponse xmlns="http://cloudformation.amazonaws.com/doc/2010-05-15/">
  <DescribeStacksResult>
    <Stacks>
      <member>
        <Outputs>
          <member>
            <Description>Current Lambda function version</Description>
            <OutputKey>WebsiteLambdaFunctionQualifiedArn</OutputKey>
            <OutputValue>arn:aws:lambda:eu-west-1:3425657435:function:sfhackday-dev-website:1</OutputValue>
          </member>
          <member>
            <Description>URL of the service endpoint</Description>
            <OutputKey>ServiceEndpoint</OutputKey>
            <OutputValue>https://etysrdhfgd.execute-api.eu-west-1.amazonaws.com/dev</OutputValue>
          </member>
          <member>
            <OutputKey>ServerlessDeploymentBucketName</OutputKey>
            <OutputValue>sbucket-sdfghggsfsa</OutputValue>
          </member>
        </Outputs>
        <Capabilities>
          <member>CAPABILITY_IAM</member>
          <member>CAPABILITY_NAMED_IAM</member>
        </Capabilities>
        <CreationTime>2019-09-23T21:03:09.483Z</CreationTime>
        <NotificationARNs/>
        <StackId>arn:aws:cloudformation:eu-west-1:45365435:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85</StackId>
        <StackName>sfhackday-dev</StackName>
        <Description>Serverless application</Description>
        <StackStatus>UPDATE_COMPLETE</StackStatus>
        <DisableRollback>false</DisableRollback>
        <Tags>
          <member>
            <Value>dev</Value>
            <Key>STAGE</Key>
          </member>
        </Tags>
        <RollbackConfiguration/>
        <DriftInformation>
          <StackDriftStatus>NOT_CHECKED</StackDriftStatus>
        </DriftInformation>
        <EnableTerminationProtection>false</EnableTerminationProtection>
        <LastUpdatedTime>2019-09-23T21:03:50.082Z</LastUpdatedTime>
      </member>
    </Stacks>
  </DescribeStacksResult>
  <ResponseMetadata>
    <RequestId>41b5466b-b8c4-45de-9bfb-87dc0d01d85e</RequestId>
  </ResponseMetadata>
</DescribeStacksResponse>

XML;

        $client = new MockHttpClient(new SimpleMockedResponse($xml));
        $result = new DescribeStacksOutput($client->request('POST', 'http://localhost'), $client);

        $stack = null;
        foreach ($result->getStacks(true) as $s) {
            $stack = $s;
        }

        self::assertInstanceOf(Stack::class, $stack, 'Could not find any stacks');
        /** @var Stack $stack */
        self::assertCount(2, $stack->getCapabilities());
        self::assertEquals(['CAPABILITY_IAM', 'CAPABILITY_NAMED_IAM'], $stack->getCapabilities());
        self::assertIsArray($stack->getNotificationARNs());
        self::assertEmpty($stack->getNotificationARNs());
        self::assertEquals('arn:aws:cloudformation:eu-west-1:45365435:stack/sfhackday-dev/dc183ba1-2120-4eef-8388-970daedb1a85', $stack->getStackId());
        self::assertEquals('sfhackday-dev', $stack->getStackName());
        self::assertEquals('Serverless application', $stack->getDescription());
        self::assertEquals('UPDATE_COMPLETE', $stack->getStackStatus());
        self::assertEquals(false, $stack->getDisableRollback());
        self::assertCount(1, $stack->getTags());
        $tag = $stack->getTags()[0];
        self::assertInstanceOf(Tag::class, $tag);
        self::assertEquals('dev', $tag->getValue());
        self::assertEquals('STAGE', $tag->getKey());
        self::assertInstanceOf(RollbackConfiguration::class, $stack->getRollbackConfiguration());
        self::assertNull($stack->getRollbackConfiguration()->getMonitoringTimeInMinutes());
        self::assertIsArray($stack->getRollbackConfiguration()->getRollbackTriggers());
        self::assertEmpty($stack->getRollbackConfiguration()->getRollbackTriggers());
        self::assertInstanceOf(StackDriftInformation::class, $stack->getDriftInformation());
        self::assertEquals('NOT_CHECKED', $stack->getDriftInformation()->getStackDriftStatus());
        self::assertFalse($stack->getEnableTerminationProtection());

        $output = $stack->getOutputs();
        self::assertCount(3, $output);
        self::assertEquals('WebsiteLambdaFunctionQualifiedArn', $output[0]->getOutputKey());
    }
}
