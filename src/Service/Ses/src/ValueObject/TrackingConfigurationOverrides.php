<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Ses\Enum\FeatureStatus;

/**
 * An object that overrides, for a single email sending request, the engagement tracking settings that would otherwise
 * apply. Use these overrides to turn open tracking or click tracking on or off for an individual message, for example
 * to suppress tracking in a transactional message that you send from an account or a configuration set that has
 * tracking enabled.
 *
 * Without an override, engagement tracking is determined by your account-level `EngagementMetrics` setting, which you
 * configure using the `PutAccountVdmAttributes` operation, by the `EngagementMetrics` setting of the configuration set
 * that the message uses, which you configure using the `PutConfigurationSetVdmOptions` operation, and by whether that
 * configuration set has an event destination whose `MatchingEventTypes` include the `OPEN` or `CLICK` event types.
 *
 * For more information about tracking open and click events, see the Amazon SES Developer Guide [^1].
 *
 * [^1]: https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing.html
 */
final class TrackingConfigurationOverrides
{
    /**
     * Specifies whether Amazon SES tracks when the recipient opens this message. Can be one of the following:
     *
     * - `ENABLED` – Amazon SES tracks opens for this message, even when your account-level and configuration set settings
     *   don't enable open tracking.
     * - `DISABLED` – Amazon SES doesn't track opens for this message, even when your account-level or configuration set
     *   settings enable open tracking. Amazon SES doesn't add the tracking image to the message.
     *
     * If you don't specify this value, Amazon SES uses the open tracking setting that would otherwise apply to the message.
     *
     * @var FeatureStatus::*|null
     */
    private $openTrackingEnabled;

    /**
     * Specifies whether Amazon SES tracks when the recipient clicks a link in this message. Can be one of the following:
     *
     * - `ENABLED` – Amazon SES tracks clicks for this message, even when your account-level and configuration set
     *   settings don't enable click tracking.
     * - `DISABLED` – Amazon SES doesn't track clicks for this message, even when your account-level or configuration set
     *   settings enable click tracking. Amazon SES doesn't rewrite the links in the message.
     *
     * If you don't specify this value, Amazon SES uses the click tracking setting that would otherwise apply to the
     * message.
     *
     * > Enabling open or click tracking with an override doesn't create an event destination. Amazon SES records the
     * > resulting open and click events in VDM, where you can review them using VDM metrics and Message Insights. To also
     * > receive these events at a destination that you own, the configuration set that the message uses must have an event
     * > destination that publishes open and click events.
     *
     * @var FeatureStatus::*|null
     */
    private $clickTrackingEnabled;

    /**
     * @param array{
     *   OpenTrackingEnabled?: FeatureStatus::*|null,
     *   ClickTrackingEnabled?: FeatureStatus::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->openTrackingEnabled = $input['OpenTrackingEnabled'] ?? null;
        $this->clickTrackingEnabled = $input['ClickTrackingEnabled'] ?? null;
    }

    /**
     * @param array{
     *   OpenTrackingEnabled?: FeatureStatus::*|null,
     *   ClickTrackingEnabled?: FeatureStatus::*|null,
     * }|TrackingConfigurationOverrides $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FeatureStatus::*|null
     */
    public function getClickTrackingEnabled(): ?string
    {
        return $this->clickTrackingEnabled;
    }

    /**
     * @return FeatureStatus::*|null
     */
    public function getOpenTrackingEnabled(): ?string
    {
        return $this->openTrackingEnabled;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->openTrackingEnabled) {
            if (!FeatureStatus::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "OpenTrackingEnabled" for "%s". The value "%s" is not a valid "FeatureStatus".', __CLASS__, $v));
            }
            $payload['OpenTrackingEnabled'] = $v;
        }
        if (null !== $v = $this->clickTrackingEnabled) {
            if (!FeatureStatus::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ClickTrackingEnabled" for "%s". The value "%s" is not a valid "FeatureStatus".', __CLASS__, $v));
            }
            $payload['ClickTrackingEnabled'] = $v;
        }

        return $payload;
    }
}
