<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Details about the connection between a Lambda function and an Amazon EFS file system [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-filesystem.html
 */
final class FileSystemConfig
{
    /**
     * The Amazon Resource Name (ARN) of the Amazon EFS access point that provides access to the file system.
     *
     * @var string
     */
    private $arn;

    /**
     * The path where the function can access the file system, starting with `/mnt/`.
     *
     * @var string
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
        $this->arn = $input['Arn'] ?? $this->throwException(new InvalidArgument('Missing required field "Arn".'));
        $this->localMountPath = $input['LocalMountPath'] ?? $this->throwException(new InvalidArgument('Missing required field "LocalMountPath".'));
    }

    /**
     * @param array{
     *   Arn: string,
     *   LocalMountPath: string,
     * }|FileSystemConfig $input
     */
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

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->arn;
        $payload['Arn'] = $v;
        $v = $this->localMountPath;
        $payload['LocalMountPath'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
