<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Test;

use AsyncAws\Core\Sts\Result\AssumedRoleUser;
use AsyncAws\Core\Test\ResultMockFactory;
use PHPUnit\Framework\TestCase;

class ResultMockFactoryTest extends TestCase
{
    public function testCreate()
    {
        /** @var AssumedRoleUser $result */
        $result = ResultMockFactory::create(AssumedRoleUser::class, [
            'Arn'=>'arn123',
            'AssumedRoleId'=>'foo123'
        ]);

        $this->assertInstanceOf(AssumedRoleUser::class, $result);
        $this->assertEquals('arn123', $result->getArn());
        $this->assertEquals('foo123', $result->getAssumedRoleId());
    }
}
