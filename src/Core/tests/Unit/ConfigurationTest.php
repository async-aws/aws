<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * If a configuration value is set to null, we should use default value.
     */
    public function testDefaultValues()
    {
        $config = Configuration::create(['endpoint' => 'foo']);
        $this->assertEquals('foo', $config->get('endpoint'));
        $config = Configuration::create(['endpoint' => '']);
        $this->assertEquals('', $config->get('endpoint'));

        $config = Configuration::create(['endpoint' => null]);
        $this->assertEquals('https://%service%.%region%.amazonaws.com', $config->get('endpoint'));
    }
}
