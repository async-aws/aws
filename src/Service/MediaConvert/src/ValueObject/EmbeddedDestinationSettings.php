<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings related to CEA/EIA-608 and CEA/EIA-708 (also called embedded or ancillary) captions. Set up embedded
 * captions in the same output as your video. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/embedded-output-captions.html. When you work directly in your JSON
 * job specification, include this object and any required children when you set destinationType to EMBEDDED,
 * EMBEDDED_PLUS_SCTE20, or SCTE20_PLUS_EMBEDDED.
 */
final class EmbeddedDestinationSettings
{
    /**
     * Ignore this setting unless your input captions are SCC format and your output captions are embedded in the video
     * stream. Specify a CC number for each captions channel in this output. If you have two channels, choose CC numbers
     * that aren't in the same field. For example, choose 1 and 3. For more information, see
     * https://docs.aws.amazon.com/console/mediaconvert/dual-scc-to-embedded.
     */
    private $destination608ChannelNumber;

    /**
     * Ignore this setting unless your input captions are SCC format and you want both 608 and 708 captions embedded in your
     * output stream. Optionally, specify the 708 service number for each output captions channel. Choose a different number
     * for each channel. To use this setting, also set Force 608 to 708 upconvert (Convert608To708) to Upconvert (UPCONVERT)
     * in your input captions selector settings. If you choose to upconvert but don't specify a 708 service number,
     * MediaConvert uses the number that you specify for CC channel number (destination608ChannelNumber) for the 708 service
     * number. For more information, see https://docs.aws.amazon.com/console/mediaconvert/dual-scc-to-embedded.
     */
    private $destination708ServiceNumber;

    /**
     * @param array{
     *   Destination608ChannelNumber?: null|int,
     *   Destination708ServiceNumber?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->destination608ChannelNumber = $input['Destination608ChannelNumber'] ?? null;
        $this->destination708ServiceNumber = $input['Destination708ServiceNumber'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestination608ChannelNumber(): ?int
    {
        return $this->destination608ChannelNumber;
    }

    public function getDestination708ServiceNumber(): ?int
    {
        return $this->destination708ServiceNumber;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->destination608ChannelNumber) {
            $payload['destination608ChannelNumber'] = $v;
        }
        if (null !== $v = $this->destination708ServiceNumber) {
            $payload['destination708ServiceNumber'] = $v;
        }

        return $payload;
    }
}
