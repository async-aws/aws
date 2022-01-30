<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;

/**
 * The newly created user.
 */
final class UserType
{
    /**
     * The user name of the user you want to describe.
     */
    private $username;

    /**
     * A container with information about the user type attributes.
     */
    private $attributes;

    /**
     * The creation date of the user.
     */
    private $userCreateDate;

    /**
     * The last modified date of the user.
     */
    private $userLastModifiedDate;

    /**
     * Specifies whether the user is enabled.
     */
    private $enabled;

    /**
     * The user status. This can be one of the following:.
     */
    private $userStatus;

    /**
     * The MFA options for the user.
     */
    private $mfaOptions;

    /**
     * @param array{
     *   Username?: null|string,
     *   Attributes?: null|AttributeType[],
     *   UserCreateDate?: null|\DateTimeImmutable,
     *   UserLastModifiedDate?: null|\DateTimeImmutable,
     *   Enabled?: null|bool,
     *   UserStatus?: null|UserStatusType::*,
     *   MFAOptions?: null|MFAOptionType[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->username = $input['Username'] ?? null;
        $this->attributes = isset($input['Attributes']) ? array_map([AttributeType::class, 'create'], $input['Attributes']) : null;
        $this->userCreateDate = $input['UserCreateDate'] ?? null;
        $this->userLastModifiedDate = $input['UserLastModifiedDate'] ?? null;
        $this->enabled = $input['Enabled'] ?? null;
        $this->userStatus = $input['UserStatus'] ?? null;
        $this->mfaOptions = isset($input['MFAOptions']) ? array_map([MFAOptionType::class, 'create'], $input['MFAOptions']) : null;
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
        return $this->attributes ?? [];
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @return MFAOptionType[]
     */
    public function getMfaOptions(): array
    {
        return $this->mfaOptions ?? [];
    }

    public function getUserCreateDate(): ?\DateTimeImmutable
    {
        return $this->userCreateDate;
    }

    public function getUserLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->userLastModifiedDate;
    }

    /**
     * @return UserStatusType::*|null
     */
    public function getUserStatus(): ?string
    {
        return $this->userStatus;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
