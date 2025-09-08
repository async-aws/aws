<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Returns information about a specific Git blob object.
 */
final class BlobMetadata
{
    /**
     * The full ID of the blob.
     *
     * @var string|null
     */
    private $blobId;

    /**
     * The path to the blob and associated file name, if any.
     *
     * @var string|null
     */
    private $path;

    /**
     * The file mode permissions of the blob. File mode permission codes include:
     *
     * - `100644` indicates read/write
     * - `100755` indicates read/write/execute
     * - `160000` indicates a submodule
     * - `120000` indicates a symlink
     *
     * @var string|null
     */
    private $mode;

    /**
     * @param array{
     *   blobId?: string|null,
     *   path?: string|null,
     *   mode?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->blobId = $input['blobId'] ?? null;
        $this->path = $input['path'] ?? null;
        $this->mode = $input['mode'] ?? null;
    }

    /**
     * @param array{
     *   blobId?: string|null,
     *   path?: string|null,
     *   mode?: string|null,
     * }|BlobMetadata $input
     */
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
