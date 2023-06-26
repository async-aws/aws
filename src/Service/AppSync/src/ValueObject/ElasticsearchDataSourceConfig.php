<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes an OpenSearch data source configuration.
 *
 * As of September 2021, Amazon Elasticsearch service is Amazon OpenSearch Service. This configuration is deprecated.
 * For new data sources, use OpenSearchServiceDataSourceConfig to specify an OpenSearch data source.
 */
final class ElasticsearchDataSourceConfig
{
    /**
     * The endpoint.
     *
     * @var string
     */
    private $endpoint;

    /**
     * The Amazon Web Services Region.
     *
     * @var string
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
        $this->endpoint = $input['endpoint'] ?? $this->throwException(new InvalidArgument('Missing required field "endpoint".'));
        $this->awsRegion = $input['awsRegion'] ?? $this->throwException(new InvalidArgument('Missing required field "awsRegion".'));
    }

    /**
     * @param array{
     *   endpoint: string,
     *   awsRegion: string,
     * }|ElasticsearchDataSourceConfig $input
     */
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
        $v = $this->endpoint;
        $payload['endpoint'] = $v;
        $v = $this->awsRegion;
        $payload['awsRegion'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
