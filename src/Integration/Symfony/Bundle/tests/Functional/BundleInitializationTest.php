<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle\Tests\Functional;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\InstanceProvider;
use AsyncAws\Core\Credentials\SymfonyCacheProvider;
use AsyncAws\S3\S3Client;
use AsyncAws\Ses\SesClient;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Symfony\Bundle\AsyncAwsBundle;
use AsyncAws\Symfony\Bundle\Secrets\SsmVault;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\EventListener\ConsoleProfilerListener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;
use Symfony\Component\HttpKernel\KernelInterface;

class BundleInitializationTest extends KernelTestCase
{
    public function testInitBundle()
    {
        $this->bootWithConfig([
            'default.yaml',
        ]);

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

        $container = self::$kernel->getContainer();
        self::assertFalse($container->has(SqsClient::class . ' $notFound'));
        self::assertFalse($container->has(S3Client::class . ' $foobar'));
    }

    public function testEmptyConfig()
    {
        $this->bootWithConfig([
            'empty.yaml',
        ]);

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
        $this->bootWithConfig([
            'no_services.yaml',
        ]);

        $container = self::$kernel->getContainer();
        self::assertFalse($container->has('async_aws.client.s3'));
        self::assertFalse($container->has('async_aws.client.sqs'));
        self::assertFalse($container->has(SqsClient::class));
    }

    public function testEmptyClientsKey()
    {
        $this->bootWithConfig([
            'empty_clients_key.yaml',
        ]);

        $container = self::$kernel->getContainer();
        self::assertTrue($container->has('async_aws.client.s3'));
        self::assertTrue($container->has('async_aws.client.sqs'));
        self::assertTrue($container->has(SqsClient::class));
    }

    public function testNotRegisterSqs()
    {
        $this->bootWithConfig([
            'no_service_sqs.yaml',
        ]);

        $container = self::$kernel->getContainer();
        self::assertTrue($container->has('async_aws.client.s3'));
        self::assertFalse($container->has('async_aws.client.sqs'));
        self::assertFalse($container->has(SqsClient::class));
    }

    public function testConfigOverride()
    {
        $this->bootWithConfig([
            'override.yaml',
        ]);

        $container = self::$kernel->getContainer();
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

        $this->expectException(InvalidConfigurationException::class);

        $this->bootWithConfig([
            'not_installed_service.yaml',
        ]);
    }

    public function testIssue793()
    {
        $this->bootWithConfig([
            'issue-793/default.yaml',
            'issue-793/dev.yaml',
        ]);

        $container = self::$kernel->getContainer();
        $x = $container->get(S3Client::class);
        self::assertSame('./docker/dynamodb/credentials', $x->getConfiguration()->get('sharedCredentialsFile'));
    }

    public function testIssue1758Empty()
    {
        $this->bootWithConfig([
            'issue-1758/empty.yaml',
        ]);

        self::assertServiceExists('async_aws.client.s3', S3Client::class);
        self::assertServiceExists('async_aws.credential', CacheProvider::class);
        self::assertServiceExists('async_aws.credential.memory', CacheProvider::class);
        self::assertServiceExists('async_aws.credential.cache', SymfonyCacheProvider::class);
    }

    public function testIssue1758Cache()
    {
        $this->bootWithConfig([
            'issue-1758/cache.yaml',
        ]);

        self::assertServiceExists('async_aws.client.s3', S3Client::class);
        self::assertServiceExists('async_aws.credential', CacheProvider::class);
        self::assertServiceExists('async_aws.credential.memory', CacheProvider::class);
        self::assertServiceExists('async_aws.credential.cache', SymfonyCacheProvider::class);

        $container = self::$kernel->getContainer();
        $cache = $container->get('async_aws.credential.cache');

        $r = new \ReflectionObject($cache);
        $p = $r->getProperty('cache');
        if (\PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }

        $adapter = $p->getValue($cache);
        self::assertInstanceOf(ApcuAdapter::class, $adapter);
    }

    public function testIssue1758Provider()
    {
        $this->bootWithConfig([
            'issue-1758/provider.yaml',
        ]);

        self::assertServiceExists('async_aws.client.s3', S3Client::class);

        $container = self::$kernel->getContainer();
        $client = $container->get(S3Client::class);

        $r = new \ReflectionClass(AbstractApi::class);
        $p = $r->getProperty('credentialProvider');
        if (\PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }

        $credentialProvider = $p->getValue($client);
        self::assertInstanceOf(InstanceProvider::class, $credentialProvider);
    }

    public function testIssue1758ProviderAndCache()
    {
        $this->bootWithConfig([
            'issue-1758/provider_cache.yaml',
        ]);

        self::assertServiceExists('async_aws.client.s3', S3Client::class);
        self::assertServiceExists(InstanceProvider::class . '.memory', CacheProvider::class);
        self::assertServiceExists(InstanceProvider::class . '.cache', SymfonyCacheProvider::class);

        $container = self::$kernel->getContainer();
        $client = $container->get(S3Client::class);

        $r = new \ReflectionClass(AbstractApi::class);
        $p = $r->getProperty('credentialProvider');
        if (\PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }

        $credentialProvider = $p->getValue($client);
        self::assertInstanceOf(CacheProvider::class, $credentialProvider);

        $cache = $container->get(InstanceProvider::class . '.cache');

        $r = new \ReflectionObject($cache);
        $p = $r->getProperty('cache');
        if (\PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }

        $adapter = $p->getValue($cache);
        self::assertInstanceOf(ApcuAdapter::class, $adapter);

        $p = $r->getProperty('decorated');
        if (\PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }

        $decorated = $p->getValue($cache);
        self::assertInstanceOf(InstanceProvider::class, $decorated);
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        $kernel = parent::createKernel($options);
        \assert($kernel instanceof TestKernel);

        $kernel->addTestBundle(AsyncAwsBundle::class);
        $kernel->addTestCompilerPass(new PublicServicePass('|async_aws.*|'));
        $kernel->addTestCompilerPass(new PublicServicePass('|AsyncAws\.*|'));
        $kernel->handleOptions($options);

        return $kernel;
    }

    private function assertServiceExists(string $serviceId, string $instance)
    {
        $container = self::$kernel->getContainer();
        self::assertTrue($container->has($serviceId));
        self::assertInstanceOf($instance, $container->get($serviceId));
    }

    private function bootWithConfig(array $configs): void
    {
        self::bootKernel(['config' => static function (TestKernel $kernel) use ($configs) {
            foreach ($configs as $config) {
                $kernel->addTestConfig(__DIR__ . '/Resources/config/' . $config);
            }

            // hack to assert the version of the bundle
            if (class_exists(WhenNot::class)) {
                $kernel->addTestConfig(__DIR__ . '/Resources/config/base_sf72.yaml');
            }
            if (class_exists(ConsoleProfilerListener::class)) {
                $kernel->addTestConfig(__DIR__ . '/Resources/config/base_sf64.yaml');
            }
            $kernel->addTestConfig(__DIR__ . '/Resources/config/base.yaml');
        }]);
    }
}
