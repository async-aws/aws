<?php

namespace AsyncAws\Sso\ValueObject;

/**
 * Provides information about the role that is assigned to the user.
 */
final class RoleInfo
{
    /**
     * The friendly name of the role that is assigned to the user.
     *
     * @var string|null
     */
    private $roleName;

    /**
     * The identifier of the AWS account assigned to the user.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * @param array{
     *   roleName?: string|null,
     *   accountId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->roleName = $input['roleName'] ?? null;
        $this->accountId = $input['accountId'] ?? null;
    }

    /**
     * @param array{
     *   roleName?: string|null,
     *   accountId?: string|null,
     * }|RoleInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }
}
