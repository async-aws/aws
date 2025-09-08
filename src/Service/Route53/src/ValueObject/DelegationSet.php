<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A complex type that lists the name servers in a delegation set, as well as the `CallerReference` and the `ID` for the
 * delegation set.
 */
final class DelegationSet
{
    /**
     * The ID that Amazon Route 53 assigns to a reusable delegation set.
     *
     * @var string|null
     */
    private $id;

    /**
     * The value that you specified for `CallerReference` when you created the reusable delegation set.
     *
     * @var string|null
     */
    private $callerReference;

    /**
     * A complex type that contains a list of the authoritative name servers for a hosted zone or for a reusable delegation
     * set.
     *
     * @var string[]
     */
    private $nameServers;

    /**
     * @param array{
     *   Id?: string|null,
     *   CallerReference?: string|null,
     *   NameServers: string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->callerReference = $input['CallerReference'] ?? null;
        $this->nameServers = $input['NameServers'] ?? $this->throwException(new InvalidArgument('Missing required field "NameServers".'));
    }

    /**
     * @param array{
     *   Id?: string|null,
     *   CallerReference?: string|null,
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
        return $this->nameServers;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
