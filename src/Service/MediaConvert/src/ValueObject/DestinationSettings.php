<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings associated with the destination. Will vary based on the type of destination.
 */
final class DestinationSettings
{
    /**
     * Settings associated with S3 destination.
     */
    private $s3Settings;

    /**
     * @param array{
     *   S3Settings?: null|S3DestinationSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3Settings = isset($input['S3Settings']) ? S3DestinationSettings::create($input['S3Settings']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getS3Settings(): ?S3DestinationSettings
    {
        return $this->s3Settings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->s3Settings) {
            $payload['s3Settings'] = $v->requestBody();
        }

        return $payload;
    }
}
