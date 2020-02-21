<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\Tests\Unit\DependencyInjection;

use AsyncAws\Symfony\Bundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testNoConfigIsValid(): void
    {
        $this->assertConfigurationIsValid([
            [], // no values at all
        ]);
    }

    public function testDefaultValues(): void
    {
        $this->assertProcessedConfigurationEquals([
            [],
        ], [
            'register_service' => true,
            'credential_provider' => null,
            'http_client' => null,
            'config' => [],
            'services' => [],
        ]);
    }

    public function testServiceWithAwsName(): void
    {
        $this->assertProcessedConfigurationEquals([
            ['services' => [
                'sqs' => ['config' => ['foo' => 'bar']],
            ]],
        ], [
            'services' => [
                'sqs' => [
                    'register_service' => true,
                    'config' => ['foo' => 'bar'],
                    'type' => 'sqs',
                ],
            ],
        ], 'services');
    }

    public function testServiceWithCustomName(): void
    {
        $this->assertProcessedConfigurationEquals([
            ['services' => [
                'foobar' => [
                    'type' => 'sqs',
                ],
            ]],
        ], [
            'services' => [
                'foobar' => [
                    'register_service' => true,
                    'type' => 'sqs',
                    'config' => [],
                ],
            ],
        ], 'services');
    }

    public function testServiceWithCustomNameWithoutType(): void
    {
        $this->assertConfigurationIsInvalid([
            ['services' => [
                'foobar' => [
                ],
            ]],
        ], 'The "async_aws.service.foobar" does not have a type');
    }

    public function testServiceWithCustomNameWithWrongType(): void
    {
        $this->assertConfigurationIsInvalid([
            ['services' => [
                'foobar' => [
                    'type' => 'blabla',
                ],
            ]],
        ], 'The value "blabla" is not allowed for path "async_aws.services.foobar.type"');
    }

    public function testServiceWithAwsNameWithWrongType(): void
    {
        $this->assertConfigurationIsInvalid([
            ['services' => [
                'sqs' => [
                    'type' => 's3',
                ],
            ]],
        ], 'You cannot define a service named "sqs" with type "s3"');
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
