<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that overrides settings for a single email sending request. An override applies only to the message or
 * messages in the request that contains it. It doesn't change your account-level settings, and it doesn't change the
 * configuration set that the request uses.
 *
 * A setting that you don't override keeps the value that would otherwise apply to the message. Depending on the
 * setting, that value comes from the configuration set that the message uses, from your account-level settings, or from
 * the Amazon SES default.
 */
final class ConfigurationOverrides
{
    /**
     * An object that overrides the open and click tracking settings that would otherwise apply to the message.
     *
     * @var TrackingConfigurationOverrides|null
     */
    private $tracking;

    /**
     * @param array{
     *   Tracking?: TrackingConfigurationOverrides|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tracking = isset($input['Tracking']) ? TrackingConfigurationOverrides::create($input['Tracking']) : null;
    }

    /**
     * @param array{
     *   Tracking?: TrackingConfigurationOverrides|array|null,
     * }|ConfigurationOverrides $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTracking(): ?TrackingConfigurationOverrides
    {
        return $this->tracking;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->tracking) {
            $payload['Tracking'] = $v->requestBody();
        }

        return $payload;
    }
}
