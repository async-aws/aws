<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\DirectS3Read;

/**
 * Setting controls how your function accesses data from an Amazon S3 file system.
 */
final class S3FilesConfig
{
    /**
     * Specifies if a function reads from the file system for the lowest latency, or through Amazon S3 Files feature "direct
     * Amazon S3 bucket reads" for the highest throughput. Valid values:
     *
     * - `AUTO` (default) – Direct reads are active for functions you configure with 512 MB or more of memory.
     * - `ENABLED` – Enforces all reads are directly from the Amazon S3 bucket, regardless of available memory (less than
     *   512 MB).
     * - `DISABLED` – Routes all reads through the file system, regardless of memory configuration.
     *
     * To use direct reads, you must grant the execution role the `s3:GetObject` and `s3:GetObjectVersion` permissions. If a
     * direct read fails, Lambda automatically falls back to reading through the file system.
     *
     * @var DirectS3Read::*|null
     */
    private $directS3Read;

    /**
     * @param array{
     *   DirectS3Read?: DirectS3Read::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->directS3Read = $input['DirectS3Read'] ?? null;
    }

    /**
     * @param array{
     *   DirectS3Read?: DirectS3Read::*|null,
     * }|S3FilesConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DirectS3Read::*|null
     */
    public function getDirectS3Read(): ?string
    {
        return $this->directS3Read;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->directS3Read) {
            if (!DirectS3Read::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "DirectS3Read" for "%s". The value "%s" is not a valid "DirectS3Read".', __CLASS__, $v));
            }
            $payload['DirectS3Read'] = $v;
        }

        return $payload;
    }
}
