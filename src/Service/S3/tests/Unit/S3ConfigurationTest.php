<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit;

use AsyncAws\S3\S3Configuration;
use PHPUnit\Framework\TestCase;

class S3ConfigurationTest extends TestCase
{
    /**
     * @dataProvider provideConfiguration
     */
    public function testCreate($config, $expected)
    {
        $config = S3Configuration::create($config);
        foreach ($expected as $key => $value) {
            self::assertEquals($value, $config->get($key));
        }
    }

    public function provideConfiguration(): iterable
    {
        yield 'simple config' => [['pathStyleEndpoint' => 'true'], ['pathStyleEndpoint' => 'true']];
        yield 'boolean value' => [['pathStyleEndpoint' => true], ['pathStyleEndpoint' => '1']];
        yield 'fallback to config' => [['region' => 'eu-west-1'], ['region' => 'eu-west-1']];
    }
}
