<?php

namespace AsyncAws\Core\Sts\ValueObject;

/**
 * The session's `aws:TransactionContext/IdentityProvider/*` keys.
 */
final class TransactionContext
{
    /**
     * The session's `aws:TransactionContext/IdentityProvider/*` keys, keyed by the part of the key name after
     * `aws:TransactionContext/IdentityProvider/`. Each value is up to 128 characters.
     *
     * The number of entries is bounded twice. IAM limits how many keys a context provider maps when you configure it on the
     * identity provider, and Amazon Web Services STS rejects a session whose session token exceeds the maximum size with
     * `PackedPolicyTooLarge`.
     *
     * @var array<string, string>|null
     */
    private $identityProvider;

    /**
     * @param array{
     *   IdentityProvider?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->identityProvider = $input['IdentityProvider'] ?? null;
    }

    /**
     * @param array{
     *   IdentityProvider?: array<string, string>|null,
     * }|TransactionContext $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getIdentityProvider(): array
    {
        return $this->identityProvider ?? [];
    }
}
