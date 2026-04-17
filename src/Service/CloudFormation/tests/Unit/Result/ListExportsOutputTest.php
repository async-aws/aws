<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\ListExportsOutput;
use AsyncAws\CloudFormation\ValueObject\Export;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListExportsOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_ListExports.html
     */
    public function testListExportsOutput(): void
    {
        $response = new SimpleMockedResponse('<ListExportsResponse xmlns="http://cloudformation.amazonaws.com/doc/2010-05-15/">
  <ListExportsResult>
    <Exports>
      <member>
        <ExportingStackId>arn:aws:cloudformation:eu-west-1:123456789012:stack/producer/abc123</ExportingStackId>
        <Name>SharedVpcId</Name>
        <Value>vpc-0abcd1234ef567890</Value>
      </member>
      <member>
        <ExportingStackId>arn:aws:cloudformation:eu-west-1:123456789012:stack/producer/abc123</ExportingStackId>
        <Name>SharedSubnetId</Name>
        <Value>subnet-0123456789abcdef0</Value>
      </member>
    </Exports>
    <NextToken>next-page-token</NextToken>
  </ListExportsResult>
  <ResponseMetadata>
    <RequestId>0b836460-dcb3-4e4d-8561-18a3c39bb5aa</RequestId>
  </ResponseMetadata>
</ListExportsResponse>
');

        $client = new MockHttpClient($response);
        $result = new ListExportsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        /** @var Export[] $exports */
        $exports = [];
        foreach ($result->getExports(true) as $e) {
            $exports[] = $e;
        }

        self::assertCount(2, $exports);
        self::assertInstanceOf(Export::class, $exports[0]);
        self::assertEquals('arn:aws:cloudformation:eu-west-1:123456789012:stack/producer/abc123', $exports[0]->getExportingStackId());
        self::assertEquals('SharedVpcId', $exports[0]->getName());
        self::assertEquals('vpc-0abcd1234ef567890', $exports[0]->getValue());
        self::assertEquals('SharedSubnetId', $exports[1]->getName());
        self::assertEquals('subnet-0123456789abcdef0', $exports[1]->getValue());

        self::assertSame('next-page-token', $result->getNextToken());
    }
}
