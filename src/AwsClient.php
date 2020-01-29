<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use WorkingTitle\Aws\Exception\MissingDependency;

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

    /**
     *
     * @param HttpClientInterface $httpClient
     * @param Configuration $configuration
     */
    public function __construct(HttpClientInterface $httpClient, Configuration $configuration)
    {
        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
    }

    public function sqs(): SqsClient
    {
        if (!class_exists(SqsClient::class)) {
            throw new MissingDependency('working-title/sqs', 'SQS');
        }

        if (!isset($this->serviceCache['sqs'])) {
            $this->serviceCache['sqs'] = new SqsClient($this->httpClient, $this->configuration);
        }

        return $this->serviceCache['sqs'];
    }
}