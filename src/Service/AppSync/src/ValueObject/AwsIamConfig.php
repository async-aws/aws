<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The Identity and Access Management settings.
 */
final class AwsIamConfig
{
    /**
     * The signing region for Identity and Access Management authorization.
     */
    private $signingRegion;

    /**
     * The signing service name for Identity and Access Management authorization.
     */
    private $signingServiceName;

    /**
     * @param array{
     *   signingRegion?: null|string,
     *   signingServiceName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->signingRegion = $input['signingRegion'] ?? null;
        $this->signingServiceName = $input['signingServiceName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSigningRegion(): ?string
    {
        return $this->signingRegion;
    }

    public function getSigningServiceName(): ?string
    {
        return $this->signingServiceName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->signingRegion) {
            $payload['signingRegion'] = $v;
        }
        if (null !== $v = $this->signingServiceName) {
            $payload['signingServiceName'] = $v;
        }

        return $payload;
    }
}
