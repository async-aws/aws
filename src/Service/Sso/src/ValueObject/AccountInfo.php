<?php

namespace AsyncAws\Sso\ValueObject;

/**
 * Provides information about your AWS account.
 */
final class AccountInfo
{
    /**
     * The identifier of the AWS account that is assigned to the user.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * The display name of the AWS account that is assigned to the user.
     *
     * @var string|null
     */
    private $accountName;

    /**
     * The email address of the AWS account that is assigned to the user.
     *
     * @var string|null
     */
    private $emailAddress;

    /**
     * @param array{
     *   accountId?: string|null,
     *   accountName?: string|null,
     *   emailAddress?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accountId = $input['accountId'] ?? null;
        $this->accountName = $input['accountName'] ?? null;
        $this->emailAddress = $input['emailAddress'] ?? null;
    }

    /**
     * @param array{
     *   accountId?: string|null,
     *   accountName?: string|null,
     *   emailAddress?: string|null,
     * }|AccountInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getAccountName(): ?string
    {
        return $this->accountName;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }
}
