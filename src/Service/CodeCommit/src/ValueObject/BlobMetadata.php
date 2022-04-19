<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Information about a `beforeBlob` data type object, including the ID, the file mode permission code, and the path.
 */
final class BlobMetadata
{
    /**
     * The full ID of the blob.
     */
    private $blobId;

    /**
     * The path to the blob and associated file name, if any.
     */
    private $path;

    /**
     * The file mode permissions of the blob. File mode permission codes include:.
     */
    private $mode;

    /**
     * @param array{
     *   blobId?: null|string,
     *   path?: null|string,
     *   mode?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->blobId = $input['blobId'] ?? null;
        $this->path = $input['path'] ?? null;
        $this->mode = $input['mode'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBlobId(): ?string
    {
        return $this->blobId;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
