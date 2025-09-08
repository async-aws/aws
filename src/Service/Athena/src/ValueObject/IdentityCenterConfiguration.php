<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Specifies whether the workgroup is IAM Identity Center supported.
 */
final class IdentityCenterConfiguration
{
    /**
     * Specifies whether the workgroup is IAM Identity Center supported.
     *
     * @var bool|null
     */
    private $enableIdentityCenter;

    /**
     * The IAM Identity Center instance ARN that the workgroup associates to.
     *
     * @var string|null
     */
    private $identityCenterInstanceArn;

    /**
     * @param array{
     *   EnableIdentityCenter?: bool|null,
     *   IdentityCenterInstanceArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enableIdentityCenter = $input['EnableIdentityCenter'] ?? null;
        $this->identityCenterInstanceArn = $input['IdentityCenterInstanceArn'] ?? null;
    }

    /**
     * @param array{
     *   EnableIdentityCenter?: bool|null,
     *   IdentityCenterInstanceArn?: string|null,
     * }|IdentityCenterConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnableIdentityCenter(): ?bool
    {
        return $this->enableIdentityCenter;
    }

    public function getIdentityCenterInstanceArn(): ?string
    {
        return $this->identityCenterInstanceArn;
    }
}
