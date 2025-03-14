<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A video source. You can upload a smaller video as a base64-encoded string as long as the encoded file is less than
 * 25MB. You can also transfer videos up to 1GB in size from an S3 bucket.
 */
final class VideoSource
{
    /**
     * Video content encoded in base64.
     *
     * @var string|null
     */
    private $bytes;

    /**
     * The location of a video object in an S3 bucket.
     *
     * @var S3Location|null
     */
    private $s3Location;

    /**
     * @param array{
     *   bytes?: null|string,
     *   s3Location?: null|S3Location|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bytes = $input['bytes'] ?? null;
        $this->s3Location = isset($input['s3Location']) ? S3Location::create($input['s3Location']) : null;
    }

    /**
     * @param array{
     *   bytes?: null|string,
     *   s3Location?: null|S3Location|array,
     * }|VideoSource $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBytes(): ?string
    {
        return $this->bytes;
    }

    public function getS3Location(): ?S3Location
    {
        return $this->s3Location;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bytes) {
            $payload['bytes'] = base64_encode($v);
        }
        if (null !== $v = $this->s3Location) {
            $payload['s3Location'] = $v->requestBody();
        }

        return $payload;
    }
}
