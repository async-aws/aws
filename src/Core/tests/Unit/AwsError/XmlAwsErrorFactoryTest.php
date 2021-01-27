<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\AwsError;

use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use PHPUnit\Framework\TestCase;

class XmlAwsErrorFactoryTest extends TestCase
{
    public function testCreateFromContent()
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

        $factory = new XmlAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertEquals('NoSuchKey', $awsError->getCode());
        self::assertEquals('The specified key does not exist.', $awsError->getMessage());
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

        $factory = new XmlAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertSame('Sender', $awsError->getType());
        self::assertSame('ValidationError', $awsError->getCode());
        self::assertSame('Stack [foo-dev] does not exist', $awsError->getMessage());
    }
}
