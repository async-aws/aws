<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Iam\Enum\StatusType;

/**
 * Contains additional details about a service-specific credential.
 */
final class ServiceSpecificCredentialMetadata
{
    /**
     * The name of the IAM user associated with the service-specific credential.
     *
     * @var string
     */
    private $userName;

    /**
     * The status of the service-specific credential. `Active` means that the key is valid for API calls, while `Inactive`
     * means it is not.
     *
     * @var StatusType::*
     */
    private $status;

    /**
     * The generated user name for the service-specific credential.
     *
     * @var string
     */
    private $serviceUserName;

    /**
     * The date and time, in ISO 8601 date-time format [^1], when the service-specific credential were created.
     *
     * [^1]: http://www.iso.org/iso/iso8601
     *
     * @var \DateTimeImmutable
     */
    private $createDate;

    /**
     * The unique identifier for the service-specific credential.
     *
     * @var string
     */
    private $serviceSpecificCredentialId;

    /**
     * The name of the service associated with the service-specific credential.
     *
     * @var string
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
        $this->userName = $input['UserName'] ?? $this->throwException(new InvalidArgument('Missing required field "UserName".'));
        $this->status = $input['Status'] ?? $this->throwException(new InvalidArgument('Missing required field "Status".'));
        $this->serviceUserName = $input['ServiceUserName'] ?? $this->throwException(new InvalidArgument('Missing required field "ServiceUserName".'));
        $this->createDate = $input['CreateDate'] ?? $this->throwException(new InvalidArgument('Missing required field "CreateDate".'));
        $this->serviceSpecificCredentialId = $input['ServiceSpecificCredentialId'] ?? $this->throwException(new InvalidArgument('Missing required field "ServiceSpecificCredentialId".'));
        $this->serviceName = $input['ServiceName'] ?? $this->throwException(new InvalidArgument('Missing required field "ServiceName".'));
    }

    /**
     * @param array{
     *   UserName: string,
     *   Status: StatusType::*,
     *   ServiceUserName: string,
     *   CreateDate: \DateTimeImmutable,
     *   ServiceSpecificCredentialId: string,
     *   ServiceName: string,
     * }|ServiceSpecificCredentialMetadata $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
