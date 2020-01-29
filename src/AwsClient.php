<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\HttpClientInterface;
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
        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
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
