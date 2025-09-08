<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * OutputChannel mapping settings.
 */
final class OutputChannelMapping
{
    /**
     * Use this setting to specify your remix values when they are integers, such as -10, 0, or 4.
     *
     * @var int[]|null
     */
    private $inputChannels;

    /**
     * Use this setting to specify your remix values when they have a decimal component, such as -10.312, 0.08, or 4.9.
     * MediaConvert rounds your remixing values to the nearest thousandth.
     *
     * @var float[]|null
     */
    private $inputChannelsFineTune;

    /**
     * @param array{
     *   InputChannels?: int[]|null,
     *   InputChannelsFineTune?: float[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->inputChannels = $input['InputChannels'] ?? null;
        $this->inputChannelsFineTune = $input['InputChannelsFineTune'] ?? null;
    }

    /**
     * @param array{
     *   InputChannels?: int[]|null,
     *   InputChannelsFineTune?: float[]|null,
     * }|OutputChannelMapping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return int[]
     */
    public function getInputChannels(): array
    {
        return $this->inputChannels ?? [];
    }

    /**
     * @return float[]
     */
    public function getInputChannelsFineTune(): array
    {
        return $this->inputChannelsFineTune ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->inputChannels) {
            $index = -1;
            $payload['inputChannels'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['inputChannels'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->inputChannelsFineTune) {
            $index = -1;
            $payload['inputChannelsFineTune'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['inputChannelsFineTune'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
