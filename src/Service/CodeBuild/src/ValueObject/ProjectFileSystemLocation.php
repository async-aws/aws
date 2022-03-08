<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\FileSystemType;

/**
 * Information about a file system created by Amazon Elastic File System (EFS). For more information, see What Is Amazon
 * Elastic File System?
 *
 * @see https://docs.aws.amazon.com/efs/latest/ug/whatisefs.html
 */
final class ProjectFileSystemLocation
{
    /**
     * The type of the file system. The one supported type is `EFS`.
     */
    private $type;

    /**
     * A string that specifies the location of the file system created by Amazon EFS. Its format is
     * `efs-dns-name:/directory-path`. You can find the DNS name of file system when you view it in the Amazon EFS console.
     * The directory path is a path to a directory in the file system that CodeBuild mounts. For example, if the DNS name of
     * a file system is `fs-abcd1234.efs.us-west-2.amazonaws.com`, and its mount directory is `my-efs-mount-directory`, then
     * the `location` is `fs-abcd1234.efs.us-west-2.amazonaws.com:/my-efs-mount-directory`.
     */
    private $location;

    /**
     * The location in the container where you mount the file system.
     */
    private $mountPoint;

    /**
     * The name used to access a file system created by Amazon EFS. CodeBuild creates an environment variable by appending
     * the `identifier` in all capital letters to `CODEBUILD_`. For example, if you specify `my_efs` for `identifier`, a new
     * environment variable is create named `CODEBUILD_MY_EFS`.
     */
    private $identifier;

    /**
     * The mount options for a file system created by Amazon EFS. The default mount options used by CodeBuild are
     * `nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2`. For more information, see Recommended NFS Mount
     * Options.
     *
     * @see https://docs.aws.amazon.com/efs/latest/ug/mounting-fs-nfs-mount-settings.html
     */
    private $mountOptions;

    /**
     * @param array{
     *   type?: null|FileSystemType::*,
     *   location?: null|string,
     *   mountPoint?: null|string,
     *   identifier?: null|string,
     *   mountOptions?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? null;
        $this->location = $input['location'] ?? null;
        $this->mountPoint = $input['mountPoint'] ?? null;
        $this->identifier = $input['identifier'] ?? null;
        $this->mountOptions = $input['mountOptions'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getMountOptions(): ?string
    {
        return $this->mountOptions;
    }

    public function getMountPoint(): ?string
    {
        return $this->mountPoint;
    }

    /**
     * @return FileSystemType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
