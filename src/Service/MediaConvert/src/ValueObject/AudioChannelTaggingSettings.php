<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioChannelTag;

/**
 * Specify the QuickTime audio channel layout tags for the audio channels in this audio track. When you don't specify a
 * value, MediaConvert labels your track as Center (C) by default. To use Audio layout tagging, your output must be in a
 * QuickTime (MOV) container and your audio codec must be AAC, WAV, or AIFF.
 */
final class AudioChannelTaggingSettings
{
    /**
     * Specify the QuickTime audio channel layout tags for the audio channels in this audio track. Enter channel layout tags
     * in the same order as your output's audio channel order. For example, if your output audio track has a left and a
     * right channel, enter Left (L) for the first channel and Right (R) for the second. If your output has multiple
     * single-channel audio tracks, enter a single channel layout tag for each track.
     *
     * @var AudioChannelTag::*|null
     */
    private $channelTag;

    /**
     * Specify the QuickTime audio channel layout tags for the audio channels in this audio track. Enter channel layout tags
     * in the same order as your output's audio channel order. For example, if your output audio track has a left and a
     * right channel, enter Left (L) for the first channel and Right (R) for the second. If your output has multiple
     * single-channel audio tracks, enter a single channel layout tag for each track.
     *
     * @var list<AudioChannelTag::*>|null
     */
    private $channelTags;

    /**
     * @param array{
     *   ChannelTag?: AudioChannelTag::*|null,
     *   ChannelTags?: array<AudioChannelTag::*>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->channelTag = $input['ChannelTag'] ?? null;
        $this->channelTags = $input['ChannelTags'] ?? null;
    }

    /**
     * @param array{
     *   ChannelTag?: AudioChannelTag::*|null,
     *   ChannelTags?: array<AudioChannelTag::*>|null,
     * }|AudioChannelTaggingSettings $input
     */
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
     * @return list<AudioChannelTag::*>
     */
    public function getChannelTags(): array
    {
        return $this->channelTags ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->channelTag) {
            if (!AudioChannelTag::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "channelTag" for "%s". The value "%s" is not a valid "AudioChannelTag".', __CLASS__, $v));
            }
            $payload['channelTag'] = $v;
        }
        if (null !== $v = $this->channelTags) {
            $index = -1;
            $payload['channelTags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!AudioChannelTag::exists($listValue)) {
                    /** @psalm-suppress NoValue */
                    throw new InvalidArgument(\sprintf('Invalid parameter "channelTags" for "%s". The value "%s" is not a valid "AudioChannelTag".', __CLASS__, $listValue));
                }
                $payload['channelTags'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
