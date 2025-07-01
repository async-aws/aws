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
     * @var string|null
     */
    private $serviceUserName;

    /**
     * For Bedrock API keys, this is the public portion of the credential that includes the IAM user name and a suffix
     * containing version and creation information.
     *
     * @var string|null
     */
    private $serviceCredentialAlias;

    /**
     * The date and time, in ISO 8601 date-time format [^1], when the service-specific credential were created.
     *
     * [^1]: http://www.iso.org/iso/iso8601
     *
     * @var \DateTimeImmutable
     */
    private $createDate;

    /**
     * The date and time when the service specific credential expires. This field is only present for Bedrock API keys that
     * were created with an expiration period.
     *
     * @var \DateTimeImmutable|null
     */
    private $expirationDate;

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
     *   ServiceUserName?: null|string,
     *   ServiceCredentialAlias?: null|string,
     *   CreateDate: \DateTimeImmutable,
     *   ExpirationDate?: null|\DateTimeImmutable,
     *   ServiceSpecificCredentialId: string,
     *   ServiceName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->userName = $input['UserName'] ?? $this->throwException(new InvalidArgument('Missing required field "UserName".'));
        $this->status = $input['Status'] ?? $this->throwException(new InvalidArgument('Missing required field "Status".'));
        $this->serviceUserName = $input['ServiceUserName'] ?? null;
        $this->serviceCredentialAlias = $input['ServiceCredentialAlias'] ?? null;
        $this->createDate = $input['CreateDate'] ?? $this->throwException(new InvalidArgument('Missing required field "CreateDate".'));
        $this->expirationDate = $input['ExpirationDate'] ?? null;
        $this->serviceSpecificCredentialId = $input['ServiceSpecificCredentialId'] ?? $this->throwException(new InvalidArgument('Missing required field "ServiceSpecificCredentialId".'));
        $this->serviceName = $input['ServiceName'] ?? $this->throwException(new InvalidArgument('Missing required field "ServiceName".'));
    }

    /**
     * @param array{
     *   UserName: string,
     *   Status: StatusType::*,
     *   ServiceUserName?: null|string,
     *   ServiceCredentialAlias?: null|string,
     *   CreateDate: \DateTimeImmutable,
     *   ExpirationDate?: null|\DateTimeImmutable,
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

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function getServiceCredentialAlias(): ?string
    {
        return $this->serviceCredentialAlias;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getServiceSpecificCredentialId(): string
    {
        return $this->serviceSpecificCredentialId;
    }

    public function getServiceUserName(): ?string
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
