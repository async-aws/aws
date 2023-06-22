<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * A complex type that lists the name servers in a delegation set, as well as the `CallerReference` and the `ID` for the
 * delegation set.
 */
final class DelegationSet
{
    /**
     * The ID that Amazon Route 53 assigns to a reusable delegation set.
     */
    private $id;

    /**
     * The value that you specified for `CallerReference` when you created the reusable delegation set.
     */
    private $callerReference;

    /**
     * A complex type that contains a list of the authoritative name servers for a hosted zone or for a reusable delegation
     * set.
     */
    private $nameServers;

    /**
     * @param array{
     *   Id?: null|string,
     *   CallerReference?: null|string,
     *   NameServers: string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->callerReference = $input['CallerReference'] ?? null;
        $this->nameServers = $input['NameServers'] ?? null;
    }

    /**
     * @param array{
     *   Id?: null|string,
     *   CallerReference?: null|string,
     *   NameServers: string[],
     * }|DelegationSet $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCallerReference(): ?string
    {
        return $this->callerReference;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getNameServers(): array
    {
        return $this->nameServers ?? [];
    }
}
