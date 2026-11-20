<?php

namespace AsyncAws\Core\Sts\ValueObject;

/**
 * The trusted identity propagation context that Amazon Web Services STS set in a session from the identity provider's
 * token. A member is absent when the session has none of its keys.
 */
final class TrustedIdentityPropagationContext
{
    /**
     * The identity of the end user on whose behalf the session acts.
     *
     * @var OriginIdentity|null
     */
    private $originIdentity;

    /**
     * Additional claims about the request that the identity provider asserted.
     *
     * @var TransactionContext|null
     */
    private $transactionContext;

    /**
     * @param array{
     *   OriginIdentity?: OriginIdentity|array|null,
     *   TransactionContext?: TransactionContext|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->originIdentity = isset($input['OriginIdentity']) ? OriginIdentity::create($input['OriginIdentity']) : null;
        $this->transactionContext = isset($input['TransactionContext']) ? TransactionContext::create($input['TransactionContext']) : null;
    }

    /**
     * @param array{
     *   OriginIdentity?: OriginIdentity|array|null,
     *   TransactionContext?: TransactionContext|array|null,
     * }|TrustedIdentityPropagationContext $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getOriginIdentity(): ?OriginIdentity
    {
        return $this->originIdentity;
    }

    public function getTransactionContext(): ?TransactionContext
    {
        return $this->transactionContext;
    }
}
