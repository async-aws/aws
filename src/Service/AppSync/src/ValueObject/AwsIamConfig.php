<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The Identity and Access Management (IAM) configuration.
 */
final class AwsIamConfig
{
    /**
     * The signing Amazon Web Services Region for IAM authorization.
     *
     * @var string|null
     */
    private $signingRegion;

    /**
     * The signing service name for IAM authorization.
     *
     * @var string|null
     */
    private $signingServiceName;

    /**
     * @param array{
     *   signingRegion?: string|null,
     *   signingServiceName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->signingRegion = $input['signingRegion'] ?? null;
        $this->signingServiceName = $input['signingServiceName'] ?? null;
    }

    /**
     * @param array{
     *   signingRegion?: string|null,
     *   signingServiceName?: string|null,
     * }|AwsIamConfig $input
     */
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
