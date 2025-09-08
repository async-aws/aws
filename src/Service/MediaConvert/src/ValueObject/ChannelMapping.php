<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Channel mapping contains the group of fields that hold the remixing value for each channel, in dB. Specify remix
 * values to indicate how much of the content from your input audio channel you want in your output audio channels. Each
 * instance of the InputChannels or InputChannelsFineTune array specifies these values for one output channel. Use one
 * instance of this array for each output channel. In the console, each array corresponds to a column in the graphical
 * depiction of the mapping matrix. The rows of the graphical matrix correspond to input channels. Valid values are
 * within the range from -60 (mute) through 6. A setting of 0 passes the input channel unchanged to the output channel
 * (no attenuation or amplification). Use InputChannels or InputChannelsFineTune to specify your remix values. Don't use
 * both.
 */
final class ChannelMapping
{
    /**
     * In your JSON job specification, include one child of OutputChannels for each audio channel that you want in your
     * output. Each child should contain one instance of InputChannels or InputChannelsFineTune.
     *
     * @var OutputChannelMapping[]|null
     */
    private $outputChannels;

    /**
     * @param array{
     *   OutputChannels?: array<OutputChannelMapping|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->outputChannels = isset($input['OutputChannels']) ? array_map([OutputChannelMapping::class, 'create'], $input['OutputChannels']) : null;
    }

    /**
     * @param array{
     *   OutputChannels?: array<OutputChannelMapping|array>|null,
     * }|ChannelMapping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return OutputChannelMapping[]
     */
    public function getOutputChannels(): array
    {
        return $this->outputChannels ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->outputChannels) {
            $index = -1;
            $payload['outputChannels'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['outputChannels'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
