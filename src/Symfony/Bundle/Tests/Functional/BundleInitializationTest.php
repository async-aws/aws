<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\Tests\Functional;

use AsyncAws\S3\S3Client;
use AsyncAws\Ses\SesClient;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Symfony\Bundle\AsyncAwsBundle;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class BundleInitializationTest extends BaseBundleTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->addCompilerPass(new PublicServicePass('|async_aws.*|'));
        $this->addCompilerPass(new PublicServicePass('|AsyncAws\.*|'));
    }

    public function testInitBundle()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/default.yaml');
        $this->bootKernel();

        $this->assertServiceExists('async_aws.client.s3', S3Client::class);
        $this->assertServiceExists('async_aws.client.sqs', SqsClient::class);
        $this->assertServiceExists('async_aws.client.ses', SesClient::class);
        $this->assertServiceExists('async_aws.client.foobar', SqsClient::class);

        // Test autowired clients
        $this->assertServiceExists(S3Client::class, S3Client::class);
        $this->assertServiceExists(SqsClient::class, SqsClient::class);
        $this->assertServiceExists(SesClient::class, SesClient::class);

        // Test autowire by name
        $this->assertServiceExists(SqsClient::class . ' $foobar', SqsClient::class);

        $container = $this->getContainer();
        self::assertFalse($container->has(SqsClient::class . ' $notFound'));
        self::assertFalse($container->has(S3Client::class . ' $foobar'));
    }

    public function testEmptyConfig()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/empty.yaml');
        $this->bootKernel();

        $this->assertServiceExists('async_aws.client.s3', S3Client::class);
        $this->assertServiceExists('async_aws.client.sqs', SqsClient::class);
        $this->assertServiceExists('async_aws.client.ses', SesClient::class);

        // Test autowired clients
        $this->assertServiceExists(S3Client::class, S3Client::class);
        $this->assertServiceExists(SqsClient::class, SqsClient::class);
        $this->assertServiceExists(SesClient::class, SesClient::class);
    }

    public function testNotRegisterServices()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/no_services.yaml');
        $this->bootKernel();

        $container = $this->getContainer();
        self::assertFalse($container->has('async_aws.client.s3'));
        self::assertFalse($container->has('async_aws.client.sqs'));
        self::assertFalse($container->has(SqsClient::class));
    }

    public function testNotRegisterSqs()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/no_service_sqs.yaml');
        $this->bootKernel();

        $container = $this->getContainer();
        self::assertTrue($container->has('async_aws.client.s3'));
        self::assertFalse($container->has('async_aws.client.sqs'));
        self::assertFalse($container->has(SqsClient::class));
    }

    public function testExceptionWhenConfigureServiceNotInstalled()
    {
        if (class_exists(SnsClient::class)) {
            self::markTestSkipped('SNSClient is installed..');
        }

        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/not_installed_service.yaml');

        $this->expectException(InvalidConfigurationException::class);
        $this->bootKernel();
    }

    protected function getBundleClass()
    {
        return AsyncAwsBundle::class;
    }

    private function assertServiceExists(string $serviceId, string $instance)
    {
        $container = $this->getContainer();
        self::assertTrue($container->has($serviceId));
        self::assertInstanceOf($instance, $container->get($serviceId));
    }
}
