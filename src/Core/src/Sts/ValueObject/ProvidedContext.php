<?php

namespace AsyncAws\Core\Sts\ValueObject;

/**
 * Reserved for future use.
 */
final class ProvidedContext
{
    /**
     * Reserved for future use.
     *
     * @var string|null
     */
    private $providerArn;

    /**
     * Reserved for future use.
     *
     * @var string|null
     */
    private $contextAssertion;

    /**
     * @param array{
     *   ProviderArn?: null|string,
     *   ContextAssertion?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->providerArn = $input['ProviderArn'] ?? null;
        $this->contextAssertion = $input['ContextAssertion'] ?? null;
    }

    /**
     * @param array{
     *   ProviderArn?: null|string,
     *   ContextAssertion?: null|string,
     * }|ProvidedContext $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContextAssertion(): ?string
    {
        return $this->contextAssertion;
    }

    public function getProviderArn(): ?string
    {
        return $this->providerArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->providerArn) {
            $payload['ProviderArn'] = $v;
        }
        if (null !== $v = $this->contextAssertion) {
            $payload['ContextAssertion'] = $v;
        }

        return $payload;
    }
}
