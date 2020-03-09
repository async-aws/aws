<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Test;

use AsyncAws\Core\Sts\Result\AssumedRoleUser;
use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
use AsyncAws\Core\Sts\StsClient;
use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Core\Tests\Resources\ExampleResponse;
use PHPUnit\Framework\TestCase;

class ResultMockFactoryTest extends TestCase
{
    public function testCreate()
    {
        /** @var AssumeRoleResponse $result */
        $result = ResultMockFactory::create(AssumeRoleResponse::class, [
            'PackedPolicySize'=>342,
        ]);

        $this->assertInstanceOf(AssumeRoleResponse::class, $result);
        $this->assertNull($result->getAssumedRoleUser());
        $this->assertEquals(342, $result->getPackedPolicySize());
    }

    public function testCreateAndFillEmptyParams()
    {
        /** @var ExampleResponse $result */
        $result = ResultMockFactory::create(ExampleResponse::class);

        $this->assertEquals(0, $result->getInt());
        $this->assertEquals([], $result->getArray());
        $this->assertEquals(false, $result->getBool());
        $this->assertEquals(0.0, $result->getFloat());
        $this->assertEquals('', $result->getString());
    }

    public function testCreateWithInvalidParameters()
    {
        $this->expectException(\ReflectionException::class);
        ResultMockFactory::create(AssumeRoleResponse::class, [
            'Foobar'=>'arn123',
        ]);
    }

    public function testCreateWithInvalidClass()
    {
        $this->expectException(\LogicException::class);
        ResultMockFactory::create(AssumedRoleUser::class, [
            'Arn'=>'arn123',
            'AssumedRoleId'=>'foo123'
        ]);
    }
}
