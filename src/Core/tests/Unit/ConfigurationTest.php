<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Configuration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    #[DataProvider('provideConfiguration')]
    public function testCreate($config, $env, $expected)
    {
        foreach ($env as $key => $value) {
            $_SERVER[$key] = $value;
        }

        try {
            $config = Configuration::create($config);
            foreach ($expected as $key => $value) {
                self::assertEquals($value, $config->get($key));
            }
        } finally {
            foreach ($env as $key => $value) {
                unset($_SERVER[$key]);
            }
        }
    }

    public function testIsDefault()
    {
        $config = Configuration::create(['region' => 'eu-west-3']);

        self::assertTrue($config->isDefault('endpoint'));
        self::assertFalse($config->isDefault('region'));
    }

    /**
     * Make sure passing "null" is the same as no passing anything.
     */
    public function testIsDefaultWhenPassingNull()
    {
        $config = Configuration::create([
            'region' => null,
            'accessKeyId' => null,
            'accessKeySecret' => null,
        ]);

        self::assertFalse($config->has('accessKeyId'));
        self::assertFalse($config->has('accessKeySecret'));

        self::assertTrue($config->isDefault('region'));
        self::assertTrue($config->isDefault('accessKeyId'));
        self::assertTrue($config->isDefault('accessKeySecret'));

        self::assertEquals(Configuration::DEFAULT_REGION, $config->get('region'));
        self::assertNull($config->get('accessKeyId'));
        self::assertNull($config->get('accessKeySecret'));
    }

    public static function provideConfiguration(): iterable
    {
        yield 'simple config' => [['endpoint' => 'foo'], [], ['endpoint' => 'foo']];
        yield 'empty config' => [['endpoint' => ''], [], ['endpoint' => '']];
        yield 'default' => [[], [], ['endpoint' => 'https://%service%.%region%.amazonaws.com']];
        yield 'default when null' => [['endpoint' => null], [], ['endpoint' => 'https://%service%.%region%.amazonaws.com']];

        yield 'default when env missing' => [[], [], ['profile' => 'default']];
        yield 'fallback env' => [[], ['AWS_PROFILE' => 'foo'], ['profile' => 'foo']];
        yield 'fallback endpoint env' => [[], ['AWS_ENDPOINT_URL' => 'http://localhost:4566'], ['endpoint' => 'http://localhost:4566']];
        yield 'config priority on env' => [['profile' => 'bar'], ['AWS_PROFILE' => 'foo'], ['profile' => 'bar']];

        yield 'config with env group' => [['accessKeyId' => 'key'], [], ['accessKeyId' => 'key', 'sessionToken' => null]];
        yield 'config with env group 2' => [['accessKeySecret' => 'secret'], [], ['accessKeySecret' => 'secret', 'accessKeyId' => null, 'sessionToken' => null]];
        yield 'mix config and env' => [['accessKeyId' => 'key'], ['AWS_SESSION_TOKEN' => 'token'], ['accessKeyId' => 'key', 'sessionToken' => null]];
        yield 'null config with env group' => [['accessKeyId' => null], ['AWS_SESSION_TOKEN' => 'token'], ['accessKeyId' => null, 'sessionToken' => 'token']];

        yield 'boolean value' => [['pathStyleEndpoint' => true], [], ['pathStyleEndpoint' => '1']];
    }

    public function testRegionFromIniFiles()
    {
        $file = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . '/config.ini';
        file_put_contents($file, "[profile Other]\nregion=eu-north-1\n[profile MyCompany]\nregion=eu-central-1\n");

        $config = Configuration::create([
            'profile' => 'MyCompany',
            'sharedConfigFile' => $file,
        ]);

        self::assertFalse($config->isDefault('region'));
        self::assertEquals('eu-central-1', $config->get('region'));
    }
}
