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
        $this->assertEquals('foo', $config->get('endpoint'));

        $config = Configuration::create(['endpoint' => '']);
        $this->assertEquals('', $config->get('endpoint'));

        $config = Configuration::create([]);
        $this->assertEquals('https://%service%.%region%.amazonaws.com', $config->get('endpoint'));

        // If a configuration value is set to null, we should use default value.
        $config = Configuration::create(['endpoint' => null]);
        $this->assertEquals('https://%service%.%region%.amazonaws.com', $config->get('endpoint'));

        $config = Configuration::create([]);
        $this->assertEquals('default', $config->get('profile'));

        putenv('AWS_PROFILE=foo');
        $config = Configuration::create([]);
        $this->assertEquals('foo', $config->get('profile'));

        putenv('AWS_PROFILE');
        $config = Configuration::create([]);
        $this->assertEquals('default', $config->get('profile'));
    }
}
