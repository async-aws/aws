<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider provideConfiguration
     */
    public function testCreate($config, $env, $expected)
    {
        foreach ($env as $key => $value) {
            putenv("$key=$value");
        }

        try {
            $config = Configuration::create($config);
            foreach ($expected as $key => $value) {
                self::assertEquals($value, $config->get($key));
            }
        } finally {
            foreach ($env as $key => $value) {
                putenv($key);
            }
        }
    }

    public function testIsDefault()
    {
        $config = Configuration::create(['region' => 'eu-west-3']);

        self::assertTrue($config->isDefault('endpoint'));
        self::assertFalse($config->isDefault('region'));
    }

    public function provideConfiguration(): iterable
    {
        yield 'simple config' => [['endpoint' => 'foo'], [], ['endpoint' => 'foo']];
        yield 'empty config' => [['endpoint' => ''], [], ['endpoint' => '']];
        yield 'default' => [[], [], ['endpoint' => 'https://%service%.%region%.amazonaws.com']];
        yield 'default when null' => [['endpoint' => null], [], ['endpoint' => 'https://%service%.%region%.amazonaws.com']];

        yield 'default when env missing' => [[], [], ['profile' => 'default']];
        yield 'fallback env' => [[], ['AWS_PROFILE' => 'foo'], ['profile' => 'foo']];
        yield 'config priority on env' => [['profile' => 'bar'], ['AWS_PROFILE' => 'foo'], ['profile' => 'bar']];

        yield 'config with env group' => [['accessKeyId' => 'key'], [], ['accessKeyId' => 'key', 'sessionToken' => null]];
        yield 'config with env group 2' => [['accessKeySecret' => 'secret'], [], ['accessKeySecret' => 'secret', 'accessKeyId' => null, 'sessionToken' => null]];
        yield 'mix config and env' => [['accessKeyId' => 'key'], ['AWS_SESSION_TOKEN' => 'token'], ['accessKeyId' => 'key', 'sessionToken' => null]];
        yield 'null config with env group' => [['accessKeyId' => null], ['AWS_SESSION_TOKEN' => 'token'], ['accessKeyId' => null, 'sessionToken' => 'token']];
    }
}
