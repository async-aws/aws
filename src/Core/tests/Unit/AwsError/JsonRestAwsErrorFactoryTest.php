<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\AwsError;

use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use PHPUnit\Framework\TestCase;

class JsonRestAwsErrorFactoryTest extends TestCase
{
    public function testRestJsonError()
    {
        $content = '{"message":"Missing final \'@domain\'"}';

        $factory = new JsonRestAwsErrorFactory();
        $awsError = $factory->createFromContent($content, ['x-amzn-errortype' => ['foo']]);

        self::assertEquals('Missing final \'@domain\'', $awsError->getMessage());
        self::assertEquals('foo', $awsError->getCode());
    }

    public function testLambdaJsonError()
    {
        $content = '{"Type":"User","message":"Invalid Layer name: arn:aws:lambda:eu-central-2:12345:layer:foobar"}';

        $factory = new JsonRestAwsErrorFactory();
        $awsError = $factory->createFromContent($content, ['x-amzn-errortype' => ['foo']]);

        self::assertSame('user', $awsError->getType());
        self::assertSame('Invalid Layer name: arn:aws:lambda:eu-central-2:12345:layer:foobar', $awsError->getMessage());
    }
}
