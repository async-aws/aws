<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Test;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Result;
use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
use AsyncAws\Core\Sts\ValueObject\AssumedRoleUser;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Core\Tests\Resources\ExampleResponse;
use PHPUnit\Framework\TestCase;

class ResultMockFactoryTest extends TestCase
{
    public function testCreate()
    {
        /** @var AssumeRoleResponse $result */
        $result = ResultMockFactory::create(AssumeRoleResponse::class, [
            'PackedPolicySize' => 342,
        ]);

        self::assertInstanceOf(AssumeRoleResponse::class, $result);
        self::assertNull($result->getAssumedRoleUser());
        self::assertEquals(342, $result->getPackedPolicySize());
    }

    public function testCreateAndFillEmptyParams()
    {
        /** @var ExampleResponse $result */
        $result = ResultMockFactory::create(ExampleResponse::class);

        self::assertEquals(0, $result->getInt());
        self::assertEquals([], $result->getArray());
        self::assertEquals(false, $result->getBool());
        self::assertEquals(0.0, $result->getFloat());
        self::assertEquals('', $result->getString());
    }

    public function testCreateWithInvalidParameters()
    {
        $this->expectException(\ReflectionException::class);
        ResultMockFactory::create(AssumeRoleResponse::class, [
            'Foobar' => 'arn123',
        ]);
    }

    public function testCreateWithInvalidClass()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('can only be used for classes that extend "AsyncAws\Core\Result"');
        ResultMockFactory::create(AssumedRoleUser::class, [
            'Arn' => 'arn123',
            'AssumedRoleId' => 'foo123',
        ]);
    }

    public function testCreateWithResult()
    {
        $result = ResultMockFactory::create(Result::class, []);

        self::assertInstanceOf(Result::class, $result);
    }

    public function testCallToResolveDontFail()
    {
        $result = ResultMockFactory::create(Result::class, []);

        self::assertTrue($result->resolve());
    }

    public function testCreateFailing()
    {
        $result = ResultMockFactory::createFailing(Result::class, 400, 'Boom');

        $this->expectException(ClientException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessageMatches('@HTTP 400 returned for "http://localhost/".*Boom@sm');

        $result->resolve();
    }

    public function testCreateFailingWithAdditionalTypeContent()
    {
        $result = ResultMockFactory::createFailing(
            Result::class,
            400,
            'Boom',
            ['__type' => 'com.amazonaws.dynamodb.v20120810#ResourceNotFoundException']
        );

        try {
            $result->resolve();
        } catch (ClientException $e) {
            self::assertSame('ResourceNotFoundException', $e->getAwsCode());
            self::assertSame('com.amazonaws.dynamodb.v20120810#ResourceNotFoundException', $e->getAwsType());

            return;
        }

        self::fail('ClientException should be thrown');
    }
}
