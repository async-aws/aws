<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\AwsError;

use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use PHPUnit\Framework\TestCase;

class JsonRpcAwsErrorFactoryTest extends TestCase
{
    public function testDynamoDbError()
    {
        $content = '{"__type":"com.amazonaws.dynamodb.v20120810#ResourceNotFoundException","message":"Requested resource not found: Table: tablename not found"}';

        $factory = new JsonRpcAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertSame('ResourceNotFoundException', $awsError->getCode());
        self::assertSame('Requested resource not found: Table: tablename not found', $awsError->getMessage());
    }

    public function testCreateFromContent()
    {
        $content = '{"__type":"foo","Message":"capitaliazed message"}';

        $factory = new JsonRpcAwsErrorFactory();
        $awsError = $factory->createFromContent($content, []);

        self::assertSame('foo', $awsError->getCode());
        self::assertSame('capitaliazed message', $awsError->getMessage());
    }
}
