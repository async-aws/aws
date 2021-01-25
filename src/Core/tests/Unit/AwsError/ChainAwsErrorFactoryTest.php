<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\AwsError;

use AsyncAws\Core\AwsError\ChainAwsErrorFactory;
use PHPUnit\Framework\TestCase;

class ChainAwsErrorFactoryTest extends TestCase
{
    public function testRestJsonError()
    {
        $content = '{"message":"Missing final \'@domain\'"}';

        $factory = new ChainAwsErrorFactory();
        $awsError = $factory->createFromContent($content, ['x-amzn-errortype' => ['foo']]);

        self::assertEquals('Missing final \'@domain\'', $awsError->getMessage());
        self::assertEquals('foo', $awsError->getCode());
    }

    public function testRestXmlError()
    {
        $content = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Error>
    <Code>NoSuchKey</Code>
    <Message>The specified key does not exist.</Message>
    <Key>travis-ci/8173c58fa3af3cb90ab1/path.txt</Key>
    <RequestId>E35DA9F89E2F4155</RequestId>
    <HostId>fQa+jP2WL4wWRe+OFbw9H9HNqoailc7Zv+nRsjEqXjrsOuIy1ubQ1rOXA=</HostId>
</Error>
XML;

        $factory = new ChainAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertEquals('NoSuchKey', $awsError->getCode());
        self::assertEquals('The specified key does not exist.', $awsError->getMessage());
    }

    public function testDynamoDbError()
    {
        $content = '{"__type":"com.amazonaws.dynamodb.v20120810#ResourceNotFoundException","message":"Requested resource not found: Table: tablename not found"}';

        $factory = new ChainAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertSame('ResourceNotFoundException', $awsError->getCode());
        self::assertSame('Requested resource not found: Table: tablename not found', $awsError->getMessage());
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

        $factory = new ChainAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertSame('Sender', $awsError->getType());
        self::assertSame('ValidationError', $awsError->getCode());
        self::assertSame('Stack [foo-dev] does not exist', $awsError->getMessage());
    }

    public function testLambdaJsonError()
    {
        $content = '{"Type":"User","message":"Invalid Layer name: arn:aws:lambda:eu-central-2:12345:layer:foobar"}';

        $factory = new ChainAwsErrorFactory();
        $awsError = $factory->createFromContent($content, ['x-amzn-errortype' => ['foo']]);

        self::assertSame('user', $awsError->getType());
        self::assertSame('Invalid Layer name: arn:aws:lambda:eu-central-2:12345:layer:foobar', $awsError->getMessage());
    }
}
