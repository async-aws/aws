<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\Tests\Functional;

use AsyncAws\S3\S3Client;
use AsyncAws\Ses\SesClient;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Symfony\Bundle\AsyncAwsBundle;
use AsyncAws\Symfony\Bundle\Secrets\SsmVault;
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

        self::assertServiceExists('async_aws.client.s3', S3Client::class);
        self::assertServiceExists('async_aws.client.sqs', SqsClient::class);
        self::assertServiceExists('async_aws.client.ses', SesClient::class);
        self::assertServiceExists('async_aws.client.foobar', SqsClient::class);
        self::assertServiceExists('async_aws.client.secret', SsmClient::class);

        // Test autowired clients
        self::assertServiceExists(S3Client::class, S3Client::class);
        self::assertServiceExists(SqsClient::class, SqsClient::class);
        self::assertServiceExists(SesClient::class, SesClient::class);

        // Test autowire by name
        self::assertServiceExists(SqsClient::class . ' $foobar', SqsClient::class);

        // Test secret
        self::assertServiceExists(SsmVault::class, SsmVault::class);

        $container = $this->getContainer();
        self::assertFalse($container->has(SqsClient::class . ' $notFound'));
        self::assertFalse($container->has(S3Client::class . ' $foobar'));
    }

    public function testEmptyConfig()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/empty.yaml');
        $this->bootKernel();

        self::assertServiceExists('async_aws.client.s3', S3Client::class);
        self::assertServiceExists('async_aws.client.sqs', SqsClient::class);
        self::assertServiceExists('async_aws.client.ses', SesClient::class);

        // Test autowired clients
        self::assertServiceExists(S3Client::class, S3Client::class);
        self::assertServiceExists(SqsClient::class, SqsClient::class);
        self::assertServiceExists(SesClient::class, SesClient::class);
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

    public function testEmptyClientsKey()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/empty_clients_key.yaml');
        $this->bootKernel();

        $container = $this->getContainer();
        self::assertTrue($container->has('async_aws.client.s3'));
        self::assertTrue($container->has('async_aws.client.sqs'));
        self::assertTrue($container->has(SqsClient::class));
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

    public function testConfigOverride()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/override.yaml');
        $this->bootKernel();

        $container = $this->getContainer();
        self::assertTrue($container->has('async_aws.client.s3'));
        self::assertTrue($container->has('async_aws.client.ses'));
        self::assertTrue($container->has('async_aws.client.sqs'));

        /** @var SesClient $ses */
        $ses = $container->get('async_aws.client.ses');
        self::assertEquals('eu-central-1', $ses->getConfiguration()->get('region'));

        /** @var S3Client $s3 */
        $s3 = $container->get('async_aws.client.s3');
        self::assertEquals('us-west-1', $s3->getConfiguration()->get('region'));
        self::assertEquals('key', $s3->getConfiguration()->get('accessKeyId'));
        self::assertEquals('secret', $s3->getConfiguration()->get('accessKeySecret'));

        /** @var SqsClient $sqs */
        $sqs = $container->get('async_aws.client.sqs');
        self::assertEquals('eu-central-1', $sqs->getConfiguration()->get('region'));
        self::assertFalse($sqs->getConfiguration()->has('accessKeyId'));
        self::assertFalse($sqs->getConfiguration()->has('accessKeySecret'));
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

    public function testIssue793()
    {
        $kernel = $this->createKernel();
        $kernel->addConfigFile(__DIR__ . '/Resources/config/issue-793/default.yaml');
        $kernel->addConfigFile(__DIR__ . '/Resources/config/issue-793/dev.yaml');

        $this->bootKernel();
        $container = $this->getContainer();
        $x = $container->get(S3Client::class);
        self::assertSame('./docker/dynamodb/credentials', $x->getConfiguration()->get('sharedCredentialsFile'));
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
