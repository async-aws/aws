<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Iam\Enum\StatusType;

final class AccessKey
{
    /**
     * The name of the IAM user that the access key is associated with.
     */
    private $UserName;

    /**
     * The ID for this access key.
     */
    private $AccessKeyId;

    /**
     * The status of the access key. `Active` means that the key is valid for API calls, while `Inactive` means it is not.
     */
    private $Status;

    /**
     * The secret key used to sign requests.
     */
    private $SecretAccessKey;

    /**
     * The date when the access key was created.
     */
    private $CreateDate;

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
        $this->UserName = $input['UserName'] ?? null;
        $this->AccessKeyId = $input['AccessKeyId'] ?? null;
        $this->Status = $input['Status'] ?? null;
        $this->SecretAccessKey = $input['SecretAccessKey'] ?? null;
        $this->CreateDate = $input['CreateDate'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): string
    {
        return $this->AccessKeyId;
    }

    public function getCreateDate(): ?\DateTimeImmutable
    {
        return $this->CreateDate;
    }

    public function getSecretAccessKey(): string
    {
        return $this->SecretAccessKey;
    }

    /**
     * @return StatusType::*
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    public function getUserName(): string
    {
        return $this->UserName;
    }
}
