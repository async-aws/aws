<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\ImageBuilder\Enum\DiskImageFormat;

/**
 * Properties that configure export from your build instance to a compatible file format for your VM.
 */
final class S3ExportConfiguration
{
    /**
     * The name of the role that grants VM Import/Export permission to export images to your S3 bucket.
     *
     * @var string
     */
    private $roleName;

    /**
     * Export the updated image to one of the following supported disk image formats:
     *
     * - **Virtual Hard Disk (VHD)** – Compatible with Citrix Xen and Microsoft Hyper-V virtualization products.
     * - **Stream-optimized ESX Virtual Machine Disk (VMDK)** – Compatible with VMware ESX and VMware vSphere versions 4,
     *   5, and 6.
     * - **Raw** – Raw format.
     *
     * @var DiskImageFormat::*
     */
    private $diskImageFormat;

    /**
     * The S3 bucket in which to store the output disk images for your VM.
     *
     * @var string
     */
    private $s3Bucket;

    /**
     * The Amazon S3 path for the bucket where the output disk images for your VM are stored.
     *
     * @var string|null
     */
    private $s3Prefix;

    /**
     * @param array{
     *   roleName: string,
     *   diskImageFormat: DiskImageFormat::*,
     *   s3Bucket: string,
     *   s3Prefix?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->roleName = $input['roleName'] ?? $this->throwException(new InvalidArgument('Missing required field "roleName".'));
        $this->diskImageFormat = $input['diskImageFormat'] ?? $this->throwException(new InvalidArgument('Missing required field "diskImageFormat".'));
        $this->s3Bucket = $input['s3Bucket'] ?? $this->throwException(new InvalidArgument('Missing required field "s3Bucket".'));
        $this->s3Prefix = $input['s3Prefix'] ?? null;
    }

    /**
     * @param array{
     *   roleName: string,
     *   diskImageFormat: DiskImageFormat::*,
     *   s3Bucket: string,
     *   s3Prefix?: string|null,
     * }|S3ExportConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DiskImageFormat::*
     */
    public function getDiskImageFormat(): string
    {
        return $this->diskImageFormat;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function getS3Bucket(): string
    {
        return $this->s3Bucket;
    }

    public function getS3Prefix(): ?string
    {
        return $this->s3Prefix;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
