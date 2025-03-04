<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;

/**
 * A user profile in a Amazon Cognito user pool.
 */
final class UserType
{
    /**
     * The user's username.
     *
     * @var string|null
     */
    private $username;

    /**
     * Names and values of a user's attributes, for example `email`.
     *
     * @var AttributeType[]|null
     */
    private $attributes;

    /**
     * The date and time when the item was created. Amazon Cognito returns this timestamp in UNIX epoch time format. Your
     * SDK might render the output in a human-readable format like ISO 8601 or a Java `Date` object.
     *
     * @var \DateTimeImmutable|null
     */
    private $userCreateDate;

    /**
     * The date and time when the item was modified. Amazon Cognito returns this timestamp in UNIX epoch time format. Your
     * SDK might render the output in a human-readable format like ISO 8601 or a Java `Date` object.
     *
     * @var \DateTimeImmutable|null
     */
    private $userLastModifiedDate;

    /**
     * Indicates whether the user's account is enabled or disabled.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * The user status. This can be one of the following:
     *
     * - UNCONFIRMED - User has been created but not confirmed.
     * - CONFIRMED - User has been confirmed.
     * - EXTERNAL_PROVIDER - User signed in with a third-party IdP.
     * - UNKNOWN - User status isn't known.
     * - RESET_REQUIRED - User is confirmed, but the user must request a code and reset their password before they can sign
     *   in.
     * - FORCE_CHANGE_PASSWORD - The user is confirmed and the user can sign in using a temporary password, but on first
     *   sign-in, the user must change their password to a new value before doing anything else.
     *
     * @var UserStatusType::*|null
     */
    private $userStatus;

    /**
     * The user's MFA configuration.
     *
     * @var MFAOptionType[]|null
     */
    private $mfaOptions;

    /**
     * @param array{
     *   Username?: null|string,
     *   Attributes?: null|array<AttributeType|array>,
     *   UserCreateDate?: null|\DateTimeImmutable,
     *   UserLastModifiedDate?: null|\DateTimeImmutable,
     *   Enabled?: null|bool,
     *   UserStatus?: null|UserStatusType::*,
     *   MFAOptions?: null|array<MFAOptionType|array>,
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

    /**
     * @param array{
     *   Username?: null|string,
     *   Attributes?: null|array<AttributeType|array>,
     *   UserCreateDate?: null|\DateTimeImmutable,
     *   UserLastModifiedDate?: null|\DateTimeImmutable,
     *   Enabled?: null|bool,
     *   UserStatus?: null|UserStatusType::*,
     *   MFAOptions?: null|array<MFAOptionType|array>,
     * }|UserType $input
     */
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
