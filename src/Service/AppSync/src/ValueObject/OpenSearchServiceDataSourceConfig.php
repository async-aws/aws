<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes an OpenSearch data source configuration.
 */
final class OpenSearchServiceDataSourceConfig
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
        $this->endpoint = $input['endpoint'] ?? $this->throwException(new InvalidArgument('Missing required field "endpoint".'));
        $this->awsRegion = $input['awsRegion'] ?? $this->throwException(new InvalidArgument('Missing required field "awsRegion".'));
    }

    /**
     * @param array{
     *   endpoint: string,
     *   awsRegion: string,
     * }|OpenSearchServiceDataSourceConfig $input
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
