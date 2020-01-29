<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use WorkingTitle\Aws\Exception\InvalidArgument;
use WorkingTitle\Aws\Exception\MissingDependency;
use WorkingTitle\Aws\Sqs\SqsClient;

class AwsClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $serviceCache;

    public function __construct(HttpClientInterface $httpClient, Configuration $configuration)
    {
        if (is_array($configuration)) {
            $configuration = Configuration::create($configuration);
        } elseif (!$configuration instanceof Configuration) {
            throw new InvalidArgument(sprintf('Second argument to "%s::__construct()" must be an array or an instance of "%s"', __CLASS__, Configuration::class));
        }

        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
    }

    public function s3(): S3Client
    {
        if (!class_exists(S3Client::class)) {
            throw MissingDependency::create('working-title/s3', 'S3');
        }

        if (!isset($this->serviceCache['s3'])) {
            $this->serviceCache['s3'] = new S3Client($this->httpClient, $this->configuration);
        }

        return $this->serviceCache['s3'];
    }

    public function sns(): SnsClient
    {
        if (!class_exists(SnsClient::class)) {
            throw MissingDependency::create('working-title/sns', 'SNS');
        }

        if (!isset($this->serviceCache['sns'])) {
            $this->serviceCache['sns'] = new SnsClient($this->httpClient, $this->configuration);
        }

        return $this->serviceCache['sns'];
    }

    public function sqs(): SqsClient
    {
        if (!class_exists(SqsClient::class)) {
            throw MissingDependency::create('working-title/sqs', 'SQS');
        }

        if (!isset($this->serviceCache['sqs'])) {
            $this->serviceCache['sqs'] = new SqsClient($this->httpClient, $this->configuration);
        }

        return $this->serviceCache['sqs'];
    }
}
