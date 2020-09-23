<?php

namespace AsyncAws\Rekognition\ValueObject;

final class Image
{
    /**
     * Blob of image bytes up to 5 MBs.
     */
    private $Bytes;

    /**
     * Identifies an S3 object as the image source.
     */
    private $S3Object;

    /**
     * @param array{
     *   Bytes?: null|string,
     *   S3Object?: null|S3Object|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Bytes = $input['Bytes'] ?? null;
        $this->S3Object = isset($input['S3Object']) ? S3Object::create($input['S3Object']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBytes(): ?string
    {
        return $this->Bytes;
    }

    public function getS3Object(): ?S3Object
    {
        return $this->S3Object;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Bytes) {
            $payload['Bytes'] = base64_encode($v);
        }
        if (null !== $v = $this->S3Object) {
            $payload['S3Object'] = $v->requestBody();
        }

        return $payload;
    }
}
