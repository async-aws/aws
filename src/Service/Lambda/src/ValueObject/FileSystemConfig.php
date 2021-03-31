<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Details about the connection between a Lambda function and an Amazon EFS file system.
 */
final class FileSystemConfig
{
    /**
     * The Amazon Resource Name (ARN) of the Amazon EFS access point that provides access to the file system.
     */
    private $arn;

    /**
     * The path where the function can access the file system, starting with `/mnt/`.
     */
    private $localMountPath;

    /**
     * @param array{
     *   Arn: string,
     *   LocalMountPath: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->localMountPath = $input['LocalMountPath'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): string
    {
        return $this->arn;
    }

    public function getLocalMountPath(): string
    {
        return $this->localMountPath;
    }
}
