<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Input settings for MultiView Settings. You can include exactly one input as enhancement layer.
 */
final class MultiViewInput
{
    /**
     * Specify the input file S3, HTTP, or HTTPS URL for your right eye view video.
     *
     * @var string|null
     */
    private $fileInput;

    /**
     * @param array{
     *   FileInput?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fileInput = $input['FileInput'] ?? null;
    }

    /**
     * @param array{
     *   FileInput?: string|null,
     * }|MultiViewInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFileInput(): ?string
    {
        return $this->fileInput;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->fileInput) {
            $payload['fileInput'] = $v;
        }

        return $payload;
    }
}
