<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Exception\Http;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;

class ClientExceptionTest extends TestCase
{
    public function testRestJsonError()
    {
        $json = '{"message":"Missing final \'@domain\'"}';
        $response = new SimpleMockedResponse($json, ['content-type' => 'application/json'], 400);
        $exception = new ClientException($response);

        self::assertEquals('Missing final \'@domain\'', $exception->getAwsMessage());
    }

    public function testRestXmlError()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Error>
    <Code>NoSuchKey</Code>
    <Message>The specified key does not exist.</Message>
    <Key>travis-ci/8173c58fa3af3cb90ab1/path.txt</Key>
    <RequestId>E35DA9F89E2F4155</RequestId>
    <HostId>fQa+jP2WL4wWRe+OFbw9H9HNqoailc7Zv+nRsjEqXjrsOuIy1ubQ1rOXA=</HostId>
</Error>
XML;

        $response = new SimpleMockedResponse($xml, ['content-type' => 'text/xml'], 400);
        $exception = new ClientException($response);

        self::assertEquals('NoSuchKey', $exception->getAwsCode());
        self::assertEquals('The specified key does not exist.', $exception->getAwsMessage());
    }

    public function testDynamoDbError()
    {
        $json = '{"__type":"com.amazonaws.dynamodb.v20120810#ResourceNotFoundException","message":"Requested resource not found: Table: tablename not found"}';
        $response = new SimpleMockedResponse($json, ['content-type' => 'application/x-amz-json-1.0'], 400);
        $exception = new ClientException($response);

        self::assertSame(400, $exception->getCode());
        self::assertSame('ResourceNotFoundException', $exception->getAwsCode());
        self::assertSame('Requested resource not found: Table: tablename not found', $exception->getAwsMessage());
    }

    public function testCloudFormationXmlError()
    {
        $content = '<ErrorResponse xmlns="http://cloudformation.amazonaws.com/doc/2010-05-15/">
  <Error>
    <Type>Sender</Type>
    <Code>ValidationError</Code>
    <Message>Stack [foo-dev] does not exist</Message>
  </Error>
  <RequestId>f1479c09-9fff-498a-89e5-b69d211fa206</RequestId>
</ErrorResponse>
';
        $response = new SimpleMockedResponse($content, ['content-type' => 'text/xml'], 400);
        $exception = new ClientException($response);

        self::assertSame(400, $exception->getCode());
        self::assertSame('Sender', $exception->getAwsType());
        self::assertSame('ValidationError', $exception->getAwsCode());
        self::assertSame('Stack [foo-dev] does not exist', $exception->getAwsMessage());
    }

    public function testLambdaJsonError()
    {
        $content = '{"Type":"User","message":"Invalid Layer name: arn:aws:lambda:eu-central-2:12345:layer:foobar"}';
        $response = new SimpleMockedResponse($content, ['content-type' => 'application/json'], 400);
        $exception = new ClientException($response);

        self::assertSame(400, $exception->getCode());
        self::assertSame('User', $exception->getAwsType());
        self::assertSame('Invalid Layer name: arn:aws:lambda:eu-central-2:12345:layer:foobar', $exception->getAwsMessage());
    }
}
