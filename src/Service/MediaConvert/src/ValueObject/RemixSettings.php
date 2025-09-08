<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use Manual audio remixing to adjust audio levels for each audio channel in each output of your job. With audio
 * remixing, you can output more or fewer audio channels than your input audio source provides.
 */
final class RemixSettings
{
    /**
     * Optionally specify the channel in your input that contains your audio description audio signal. MediaConvert mixes
     * your audio signal across all output channels, while reducing their volume according to your data stream. When you
     * specify an audio description audio channel, you must also specify an audio description data channel. For more
     * information about audio description signals, see the BBC WHP 198 and 051 white papers.
     *
     * @var int|null
     */
    private $audioDescriptionAudioChannel;

    /**
     * Optionally specify the channel in your input that contains your audio description data stream. MediaConvert mixes
     * your audio signal across all output channels, while reducing their volume according to your data stream. When you
     * specify an audio description data channel, you must also specify an audio description audio channel. For more
     * information about audio description signals, see the BBC WHP 198 and 051 white papers.
     *
     * @var int|null
     */
    private $audioDescriptionDataChannel;

    /**
     * Channel mapping contains the group of fields that hold the remixing value for each channel, in dB. Specify remix
     * values to indicate how much of the content from your input audio channel you want in your output audio channels. Each
     * instance of the InputChannels or InputChannelsFineTune array specifies these values for one output channel. Use one
     * instance of this array for each output channel. In the console, each array corresponds to a column in the graphical
     * depiction of the mapping matrix. The rows of the graphical matrix correspond to input channels. Valid values are
     * within the range from -60 (mute) through 6. A setting of 0 passes the input channel unchanged to the output channel
     * (no attenuation or amplification). Use InputChannels or InputChannelsFineTune to specify your remix values. Don't use
     * both.
     *
     * @var ChannelMapping|null
     */
    private $channelMapping;

    /**
     * Specify the number of audio channels from your input that you want to use in your output. With remixing, you might
     * combine or split the data in these channels, so the number of channels in your final output might be different. If
     * you are doing both input channel mapping and output channel mapping, the number of output channels in your input
     * mapping must be the same as the number of input channels in your output mapping.
     *
     * @var int|null
     */
    private $channelsIn;

    /**
     * Specify the number of channels in this output after remixing. Valid values: 1, 2, 4, 6, 8... 64. (1 and even numbers
     * to 64.) If you are doing both input channel mapping and output channel mapping, the number of output channels in your
     * input mapping must be the same as the number of input channels in your output mapping.
     *
     * @var int|null
     */
    private $channelsOut;

    /**
     * @param array{
     *   AudioDescriptionAudioChannel?: int|null,
     *   AudioDescriptionDataChannel?: int|null,
     *   ChannelMapping?: ChannelMapping|array|null,
     *   ChannelsIn?: int|null,
     *   ChannelsOut?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDescriptionAudioChannel = $input['AudioDescriptionAudioChannel'] ?? null;
        $this->audioDescriptionDataChannel = $input['AudioDescriptionDataChannel'] ?? null;
        $this->channelMapping = isset($input['ChannelMapping']) ? ChannelMapping::create($input['ChannelMapping']) : null;
        $this->channelsIn = $input['ChannelsIn'] ?? null;
        $this->channelsOut = $input['ChannelsOut'] ?? null;
    }

    /**
     * @param array{
     *   AudioDescriptionAudioChannel?: int|null,
     *   AudioDescriptionDataChannel?: int|null,
     *   ChannelMapping?: ChannelMapping|array|null,
     *   ChannelsIn?: int|null,
     *   ChannelsOut?: int|null,
     * }|RemixSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAudioDescriptionAudioChannel(): ?int
    {
        return $this->audioDescriptionAudioChannel;
    }

    public function getAudioDescriptionDataChannel(): ?int
    {
        return $this->audioDescriptionDataChannel;
    }

    public function getChannelMapping(): ?ChannelMapping
    {
        return $this->channelMapping;
    }

    public function getChannelsIn(): ?int
    {
        return $this->channelsIn;
    }

    public function getChannelsOut(): ?int
    {
        return $this->channelsOut;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDescriptionAudioChannel) {
            $payload['audioDescriptionAudioChannel'] = $v;
        }
        if (null !== $v = $this->audioDescriptionDataChannel) {
            $payload['audioDescriptionDataChannel'] = $v;
        }
        if (null !== $v = $this->channelMapping) {
            $payload['channelMapping'] = $v->requestBody();
        }
        if (null !== $v = $this->channelsIn) {
            $payload['channelsIn'] = $v;
        }
        if (null !== $v = $this->channelsOut) {
            $payload['channelsOut'] = $v;
        }

        return $payload;
    }
}
