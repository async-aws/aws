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
}
