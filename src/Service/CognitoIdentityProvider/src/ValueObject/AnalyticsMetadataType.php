<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The analytics metadata for collecting Amazon Pinpoint metrics for `AdminInitiateAuth` calls.
 */
final class AnalyticsMetadataType
{
    /**
     * The endpoint ID.
     */
    private $AnalyticsEndpointId;

    /**
     * @param array{
     *   AnalyticsEndpointId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AnalyticsEndpointId = $input['AnalyticsEndpointId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAnalyticsEndpointId(): ?string
    {
        return $this->AnalyticsEndpointId;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->AnalyticsEndpointId) {
            $payload['AnalyticsEndpointId'] = $v;
        }

        return $payload;
    }
}
