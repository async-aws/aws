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
            'config' => [],
            'clients' => [],
            'secrets' => [
                'enabled' => false,
                'path' => null,
                'recursive' => true,
                'client' => null,
            ],
        ]);
    }

    public function testServiceWithAwsName(): void
    {
        $this->assertProcessedConfigurationEquals([
            ['clients' => [
                'sqs' => ['config' => ['foo' => 'bar']],
            ]],
        ], [
            'clients' => [
                'sqs' => [
                    'register_service' => true,
                    'config' => ['foo' => 'bar'],
                    'type' => 'sqs',
                ],
            ],
        ], 'clients');
    }

    public function testServiceWithCustomName(): void
    {
        $this->assertProcessedConfigurationEquals([
            ['clients' => [
                'foobar' => [
                    'type' => 'sqs',
                ],
            ]],
        ], [
            'clients' => [
                'foobar' => [
                    'register_service' => true,
                    'type' => 'sqs',
                    'config' => [],
                ],
            ],
        ], 'clients');
    }

    public function testServiceWithCustomNameWithoutType(): void
    {
        $this->assertConfigurationIsInvalid([
            ['clients' => [
                'foobar' => [
                ],
            ]],
        ], 'The "async_aws.client.foobar" does not have a type');
    }

    public function testServiceWithCustomNameWithWrongType(): void
    {
        $this->assertConfigurationIsInvalid([
            ['clients' => [
                'foobar' => [
                    'type' => 'blabla',
                ],
            ]],
        ], 'The value "blabla" is not allowed for path "async_aws.clients.foobar.type"');
    }

    public function testServiceWithAwsNameWithWrongType(): void
    {
        $this->assertConfigurationIsInvalid([
            ['clients' => [
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
