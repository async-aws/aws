<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Iam\Enum\StatusType;

/**
 * Contains additional details about a service-specific credential.
 */
final class ServiceSpecificCredentialMetadata
{
    /**
     * The name of the IAM user associated with the service-specific credential.
     */
    private $userName;

    /**
     * The status of the service-specific credential. `Active` means that the key is valid for API calls, while `Inactive`
     * means it is not.
     */
    private $status;

    /**
     * The generated user name for the service-specific credential.
     */
    private $serviceUserName;

    /**
     * The date and time, in ISO 8601 date-time format [^1], when the service-specific credential were created.
     *
     * [^1]: http://www.iso.org/iso/iso8601
     */
    private $createDate;

    /**
     * The unique identifier for the service-specific credential.
     */
    private $serviceSpecificCredentialId;

    /**
     * The name of the service associated with the service-specific credential.
     */
    private $serviceName;

    /**
     * @param array{
     *   UserName: string,
     *   Status: StatusType::*,
     *   ServiceUserName: string,
     *   CreateDate: \DateTimeImmutable,
     *   ServiceSpecificCredentialId: string,
     *   ServiceName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->userName = $input['UserName'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->serviceUserName = $input['ServiceUserName'] ?? null;
        $this->createDate = $input['CreateDate'] ?? null;
        $this->serviceSpecificCredentialId = $input['ServiceSpecificCredentialId'] ?? null;
        $this->serviceName = $input['ServiceName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getServiceSpecificCredentialId(): string
    {
        return $this->serviceSpecificCredentialId;
    }

    public function getServiceUserName(): string
    {
        return $this->serviceUserName;
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
