<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about the content to ingest into a knowledge base connected to an Amazon S3 data source.
 */
final class S3Content
{
    /**
     * The S3 location of the file containing the content to ingest.
     *
     * @var S3Location
     */
    private $s3Location;

    /**
     * @param array{
     *   s3Location: S3Location|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3Location = isset($input['s3Location']) ? S3Location::create($input['s3Location']) : $this->throwException(new InvalidArgument('Missing required field "s3Location".'));
    }

    /**
     * @param array{
     *   s3Location: S3Location|array,
     * }|S3Content $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getS3Location(): S3Location
    {
        return $this->s3Location;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->s3Location;
        $payload['s3Location'] = $v->requestBody();

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
