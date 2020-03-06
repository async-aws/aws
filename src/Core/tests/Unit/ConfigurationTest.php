<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testDefaultValues()
    {
        $config = Configuration::create(['endpoint' => 'foo']);
        self::assertEquals('foo', $config->get('endpoint'));

        $config = Configuration::create(['endpoint' => '']);
        self::assertEquals('', $config->get('endpoint'));

        $config = Configuration::create([]);
        self::assertEquals('https://%service%.%region%.amazonaws.com', $config->get('endpoint'));

        // If a configuration value is set to null, we should use default value.
        $config = Configuration::create(['endpoint' => null]);
        self::assertEquals('https://%service%.%region%.amazonaws.com', $config->get('endpoint'));

        $config = Configuration::create([]);
        self::assertEquals('default', $config->get('profile'));

        putenv('AWS_PROFILE=foo');
        $config = Configuration::create([]);
        self::assertEquals('foo', $config->get('profile'));

        putenv('AWS_PROFILE');
        $config = Configuration::create([]);
        self::assertEquals('default', $config->get('profile'));
    }
}
