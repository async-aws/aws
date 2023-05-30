<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioChannelTag;

/**
 * When you mimic a multi-channel audio layout with multiple mono-channel tracks, you can tag each channel layout
 * manually. For example, you would tag the tracks that contain your left, right, and center audio with Left (L), Right
 * (R), and Center (C), respectively. When you don't specify a value, MediaConvert labels your track as Center (C) by
 * default. To use audio layout tagging, your output must be in a QuickTime (.mov) container; your audio codec must be
 * AAC, WAV, or AIFF; and you must set up your audio track to have only one channel.
 */
final class AudioChannelTaggingSettings
{
    /**
     * You can add a tag for this mono-channel audio track to mimic its placement in a multi-channel layout. For example, if
     * this track is the left surround channel, choose Left surround (LS).
     */
    private $channelTag;

    /**
     * @param array{
     *   ChannelTag?: null|AudioChannelTag::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->channelTag = $input['ChannelTag'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AudioChannelTag::*|null
     */
    public function getChannelTag(): ?string
    {
        return $this->channelTag;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->channelTag) {
            if (!AudioChannelTag::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "channelTag" for "%s". The value "%s" is not a valid "AudioChannelTag".', __CLASS__, $v));
            }
            $payload['channelTag'] = $v;
        }

        return $payload;
    }
}
