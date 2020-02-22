<?php

declare(strict_types=1);

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\CloudFormation\Result\Stack;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStackOutputTest extends TestCase
{
    public function testPopulateResult()
    {
        $xml = <<<XML
<DescribeStacksResponse xmlns="http://cloudformation.amazonaws.com/doc/2010-05-15/">
  <DescribeStacksResult>
    <Stacks>
      <member>
        <Outputs>
          <member>
            <Description>Current Lambda function version</Description>
            <OutputKey>ConsoleLambdaFunctionQualifiedArn</OutputKey>
            <OutputValue>arn:aws:lambda:eu-central-1:34253654745:function:foobar-console:30</OutputValue>
          </member>
          <member>
            <Description>Current Lambda function version</Description>
            <OutputKey>WorkerLambdaFunctionQualifiedArn</OutputKey>
            <OutputValue>arn:aws:lambda:eu-central-1:34253654745:function:foobar-worker:21</OutputValue>
          </member>
          <member>
            <Description>Current Lambda function version</Description>
            <OutputKey>WebsiteLambdaFunctionQualifiedArn</OutputKey>
            <OutputValue>arn:aws:lambda:eu-central-1:34253654745:function:foobar-website:30</OutputValue>
          </member>
          <member>
            <Description>URL of the service endpoint</Description>
            <OutputKey>ServiceEndpoint</OutputKey>
            <OutputValue>https://p1kq4xmqt0.execute-api.eu-central-1.amazonaws.com/prod</OutputValue>
          </member>
          <member>
            <OutputKey>ServerlessDeploymentBucketName</OutputKey>
            <OutputValue>foo-lambda-applications-eu-central-1</OutputValue>
          </member>
        </Outputs>
        <Capabilities>
          <member>CAPABILITY_IAM</member>
          <member>CAPABILITY_NAMED_IAM</member>
        </Capabilities>
        <CreationTime>2020-02-16T13:48:03.325Z</CreationTime>
        <NotificationARNs/>
        <StackId>arn:aws:cloudformation:eu-central-1:34253654745:stack/foobar/f3b22b50-51c2-12ea-b958-06a323f6b6e2</StackId>
        <StackName>foobar</StackName>
        <Description>The AWS CloudFormation template for this Serverless application</Description>
        <StackStatus>UPDATE_COMPLETE</StackStatus>
        <DisableRollback>false</DisableRollback>
        <Tags>
          <member>
            <Value>prod</Value>
            <Key>STAGE</Key>
          </member>
        </Tags>
        <RollbackConfiguration/>
        <DriftInformation>
          <StackDriftStatus>NOT_CHECKED</StackDriftStatus>
        </DriftInformation>
        <EnableTerminationProtection>false</EnableTerminationProtection>
        <LastUpdatedTime>2020-02-19T22:10:24.681Z</LastUpdatedTime>
      </member>
    </Stacks>
  </DescribeStacksResult>
  <ResponseMetadata>
    <RequestId>8a082b63-a17b-4f2b-b8aa-b9fa8a550c60</RequestId>
  </ResponseMetadata>
</DescribeStacksResponse>
XML;

        $response = new SimpleMockedResponse($xml);
        $result = new DescribeStacksOutput($response, new MockHttpClient());

        $stack = null;
        foreach ($result->getStacks(true) as $s) {
            $stack = $s;
        }

        self::assertInstanceOf(Stack::class, $stack, 'Could not find any stacks');
        $output = $stack->getOutputs();
        self::assertCount(5, $output);
        self::assertEquals('ConsoleLambdaFunctionQualifiedArn', $output[0]->getOutputKey());
    }
}
