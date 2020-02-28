<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\Core\Credentials\CacheProvider;
use AsyncAws\Core\Credentials\ChainProvider;
use AsyncAws\Core\Credentials\ConfigurationProvider;
use AsyncAws\Core\Credentials\CredentialProvider;
use AsyncAws\Core\Credentials\IniFileProvider;
use AsyncAws\Core\Credentials\InstanceProvider;
use AsyncAws\Core\Credentials\WebIdentityProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Exception\MissingDependency;
use AsyncAws\Core\Sts\StsClient;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\S3\S3Client;
use AsyncAws\Ses\SesClient;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sqs\SqsClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Factory that instantiate API clients.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AwsClientFactory
{
    /**
     * @var array
     */
    private $serviceCache;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var CredentialProvider
     */
    private $credentialProvider;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @param Configuration|array $configuration
     */
    public function __construct($configuration = [], ?CredentialProvider $credentialProvider = null, ?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null)
    {
        if (\is_array($configuration)) {
            $configuration = Configuration::create($configuration);
        } elseif (!$configuration instanceof Configuration) {
            throw new InvalidArgument(sprintf('Second argument to "%s::__construct()" must be an array or an instance of "%s"', __CLASS__, Configuration::class));
        }

        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->logger = $logger ?? new NullLogger();
        $this->configuration = $configuration;
        $this->credentialProvider = $credentialProvider ?? new CacheProvider(new ChainProvider([
            new ConfigurationProvider(),
            new WebIdentityProvider($this->logger),
            new IniFileProvider($this->logger),
            new InstanceProvider($this->httpClient, $this->logger),
        ]));
    }

    public function cloudFormation(): CloudFormationClient
    {
        if (!class_exists(CloudFormationClient::class)) {
            throw MissingDependency::create('async-aws/cloud-formation', 'CloudFormation');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new CloudFormationClient($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }

    public function lambda(): LambdaClient
    {
        if (!class_exists(LambdaClient::class)) {
            throw MissingDependency::create('async-aws/lambda', 'Lambda');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new LambdaClient($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }

    public function s3(): S3Client
    {
        if (!class_exists(S3Client::class)) {
            throw MissingDependency::create('async-aws/s3', 'S3');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new S3Client($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }

    public function ses(): SesClient
    {
        if (!class_exists(SesClient::class)) {
            throw MissingDependency::create('async-aws/ses', 'SES');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new SesClient($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }

    public function sns(): SnsClient
    {
        if (!class_exists(SnsClient::class)) {
            throw MissingDependency::create('async-aws/sns', 'SNS');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new SnsClient($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }

    public function sts(): StsClient
    {
        if (!class_exists(StsClient::class)) {
            throw MissingDependency::create('async-aws/core', 'STS');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new StsClient($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }

    public function sqs(): SqsClient
    {
        if (!class_exists(SqsClient::class)) {
            throw MissingDependency::create('async-aws/sqs', 'SQS');
        }

        if (!isset($this->serviceCache[__METHOD__])) {
            $this->serviceCache[__METHOD__] = new SqsClient($this->configuration, $this->credentialProvider, $this->httpClient, $this->logger);
        }

        return $this->serviceCache[__METHOD__];
    }
}
