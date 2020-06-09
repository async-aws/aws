<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;

final class UserType
{
    /**
     * The user name of the user you wish to describe.
     */
    private $Username;

    /**
     * A container with information about the user type attributes.
     */
    private $Attributes;

    /**
     * The creation date of the user.
     */
    private $UserCreateDate;

    /**
     * The last modified date of the user.
     */
    private $UserLastModifiedDate;

    /**
     * Specifies whether the user is enabled.
     */
    private $Enabled;

    /**
     * The user status. Can be one of the following:.
     */
    private $UserStatus;

    /**
     * The MFA options for the user.
     */
    private $MFAOptions;

    /**
     * @param array{
     *   Username?: null|string,
     *   Attributes?: null|\AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   UserCreateDate?: null|\DateTimeImmutable,
     *   UserLastModifiedDate?: null|\DateTimeImmutable,
     *   Enabled?: null|bool,
     *   UserStatus?: null|\AsyncAws\CognitoIdentityProvider\Enum\UserStatusType::*,
     *   MFAOptions?: null|\AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Username = $input['Username'] ?? null;
        $this->Attributes = isset($input['Attributes']) ? array_map([AttributeType::class, 'create'], $input['Attributes']) : null;
        $this->UserCreateDate = $input['UserCreateDate'] ?? null;
        $this->UserLastModifiedDate = $input['UserLastModifiedDate'] ?? null;
        $this->Enabled = $input['Enabled'] ?? null;
        $this->UserStatus = $input['UserStatus'] ?? null;
        $this->MFAOptions = isset($input['MFAOptions']) ? array_map([MFAOptionType::class, 'create'], $input['MFAOptions']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AttributeType[]
     */
    public function getAttributes(): array
    {
        return $this->Attributes ?? [];
    }

    public function getEnabled(): ?bool
    {
        return $this->Enabled;
    }

    /**
     * @return MFAOptionType[]
     */
    public function getMFAOptions(): array
    {
        return $this->MFAOptions ?? [];
    }

    public function getUserCreateDate(): ?\DateTimeImmutable
    {
        return $this->UserCreateDate;
    }

    public function getUserLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->UserLastModifiedDate;
    }

    /**
     * @return UserStatusType::*|null
     */
    public function getUserStatus(): ?string
    {
        return $this->UserStatus;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }
}
