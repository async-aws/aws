<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The Amazon Pinpoint analytics metadata for collecting metrics for `ConfirmSignUp` calls.
 */
final class AnalyticsMetadataType
{
    /**
     * The endpoint ID.
     */
    private $analyticsEndpointId;

    /**
     * @param array{
     *   AnalyticsEndpointId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->analyticsEndpointId = $input['AnalyticsEndpointId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAnalyticsEndpointId(): ?string
    {
        return $this->analyticsEndpointId;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->analyticsEndpointId) {
            $payload['AnalyticsEndpointId'] = $v;
        }

        return $payload;
    }
}
