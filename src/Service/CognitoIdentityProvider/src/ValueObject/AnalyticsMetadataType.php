<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * An Amazon Pinpoint analytics endpoint.
 *
 * An endpoint uniquely identifies a mobile device, email address, or phone number that can receive messages from Amazon
 * Pinpoint analytics. For more information about Amazon Web Services Regions that can contain Amazon Pinpoint resources
 * for use with Amazon Cognito user pools, see Using Amazon Pinpoint analytics with Amazon Cognito user pools [^1].
 *
 * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-pinpoint-integration.html
 */
final class AnalyticsMetadataType
{
    /**
     * The endpoint ID.
     *
     * @var string|null
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

    /**
     * @param array{
     *   AnalyticsEndpointId?: null|string,
     * }|AnalyticsMetadataType $input
     */
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
