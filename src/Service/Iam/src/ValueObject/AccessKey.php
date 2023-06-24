<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Iam\Enum\StatusType;

/**
 * Contains information about an Amazon Web Services access key.
 *
 * This data type is used as a response element in the CreateAccessKey and ListAccessKeys operations.
 *
 * > The `SecretAccessKey` value is returned only in response to CreateAccessKey. You can get a secret access key only
 * > when you first create an access key; you cannot recover the secret access key later. If you lose a secret access
 * > key, you must create a new access key.
 */
final class AccessKey
{
    /**
     * The name of the IAM user that the access key is associated with.
     */
    private $userName;

    /**
     * The ID for this access key.
     */
    private $accessKeyId;

    /**
     * The status of the access key. `Active` means that the key is valid for API calls, while `Inactive` means it is not.
     */
    private $status;

    /**
     * The secret key used to sign requests.
     */
    private $secretAccessKey;

    /**
     * The date when the access key was created.
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
        $this->userName = $input['UserName'] ?? null;
        $this->accessKeyId = $input['AccessKeyId'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->secretAccessKey = $input['SecretAccessKey'] ?? null;
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
}
