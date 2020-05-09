<?php

namespace AsyncAws\Symfony\Bundle\Tests\Unit\Secrets;

use AsyncAws\Symfony\Bundle\Secrets\CachedEnvVarLoader;
use AsyncAws\Symfony\Bundle\Secrets\SsmVault;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CachedEnvVarLoaderTest extends TestCase
{
    public function testLoadEnvVars()
    {
        $env = ['FOO' => 'bar'];
        $decorated = $this->createMock(SsmVault::class);
        $decorated->expects(self::once())
            ->method('loadEnvVars')
            ->willReturn($env);

        $envLoader = new CachedEnvVarLoader($decorated, new ArrayAdapter(), 5);

        self::assertSame($env, $envLoader->loadEnvVars());
        // call it twice to assert decorated is called once
        self::assertSame($env, $envLoader->loadEnvVars());
    }
}
