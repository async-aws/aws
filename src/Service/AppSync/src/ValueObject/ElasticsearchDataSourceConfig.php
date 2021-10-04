<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The new OpenSearch configuration.
 * As of September 2021, Amazon Elasticsearch service is Amazon OpenSearch Service. This configuration is deprecated.
 * Instead, use UpdateDataSourceRequest$openSearchServiceConfig to update an OpenSearch data source.
 */
final class ElasticsearchDataSourceConfig
{
    /**
     * The endpoint.
     */
    private $endpoint;

    /**
     * The Amazon Web Services Region.
     */
    private $awsRegion;

    /**
     * @param array{
     *   endpoint: string,
     *   awsRegion: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->endpoint = $input['endpoint'] ?? null;
        $this->awsRegion = $input['awsRegion'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAwsRegion(): string
    {
        return $this->awsRegion;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->endpoint) {
            throw new InvalidArgument(sprintf('Missing parameter "endpoint" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['endpoint'] = $v;
        if (null === $v = $this->awsRegion) {
            throw new InvalidArgument(sprintf('Missing parameter "awsRegion" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['awsRegion'] = $v;

        return $payload;
    }
}
