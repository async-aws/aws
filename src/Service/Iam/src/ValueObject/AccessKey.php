<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Iam\Enum\StatusType;

/**
 * Contains information about an Amazon Web Services access key.
 *
 * This data type is used as a response element in the CreateAccessKey [^1] and ListAccessKeys [^2] operations.
 *
 * > The `SecretAccessKey` value is returned only in response to CreateAccessKey [^3]. You can get a secret access key
 * > only when you first create an access key; you cannot recover the secret access key later. If you lose a secret
 * > access key, you must create a new access key.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateAccessKey.html
 * [^2]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListAccessKeys.html
 * [^3]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateAccessKey.html
 */
final class AccessKey
{
    /**
     * The name of the IAM user that the access key is associated with.
     *
     * @var string
     */
    private $userName;

    /**
     * The ID for this access key.
     *
     * @var string
     */
    private $accessKeyId;

    /**
     * The status of the access key. `Active` means that the key is valid for API calls, while `Inactive` means it is not.
     *
     * @var StatusType::*
     */
    private $status;

    /**
     * The secret key used to sign requests.
     *
     * @var string
     */
    private $secretAccessKey;

    /**
     * The date when the access key was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $createDate;

    /**
     * @param array{
     *   UserName: string,
     *   AccessKeyId: string,
     *   Status: StatusType::*,
     *   SecretAccessKey: string,
     *   CreateDate?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->userName = $input['UserName'] ?? $this->throwException(new InvalidArgument('Missing required field "UserName".'));
        $this->accessKeyId = $input['AccessKeyId'] ?? $this->throwException(new InvalidArgument('Missing required field "AccessKeyId".'));
        $this->status = $input['Status'] ?? $this->throwException(new InvalidArgument('Missing required field "Status".'));
        $this->secretAccessKey = $input['SecretAccessKey'] ?? $this->throwException(new InvalidArgument('Missing required field "SecretAccessKey".'));
        $this->createDate = $input['CreateDate'] ?? null;
    }

    /**
     * @param array{
     *   UserName: string,
     *   AccessKeyId: string,
     *   Status: StatusType::*,
     *   SecretAccessKey: string,
     *   CreateDate?: null|\DateTimeImmutable,
     * }|AccessKey $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): string
    {
        return $this->accessKeyId;
    }

    public function getCreateDate(): ?\DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getSecretAccessKey(): string
    {
        return $this->secretAccessKey;
    }

    /**
     * @return StatusType::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
