<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Logging configuration defines where Image Builder uploads your logs.
 */
final class Logging
{
    /**
     * The Amazon S3 logging configuration.
     *
     * @var S3Logs|null
     */
    private $s3Logs;

    /**
     * @param array{
     *   s3Logs?: S3Logs|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3Logs = isset($input['s3Logs']) ? S3Logs::create($input['s3Logs']) : null;
    }

    /**
     * @param array{
     *   s3Logs?: S3Logs|array|null,
     * }|Logging $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getS3Logs(): ?S3Logs
    {
        return $this->s3Logs;
    }
}
