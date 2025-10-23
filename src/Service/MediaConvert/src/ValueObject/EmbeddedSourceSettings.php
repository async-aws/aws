<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\EmbeddedConvert608To708;
use AsyncAws\MediaConvert\Enum\EmbeddedTerminateCaptions;

/**
 * Settings for embedded captions Source.
 */
final class EmbeddedSourceSettings
{
    /**
     * Specify whether this set of input captions appears in your outputs in both 608 and 708 format. If you choose
     * Upconvert, MediaConvert includes the captions data in two ways: it passes the 608 data through using the 608
     * compatibility bytes fields of the 708 wrapper, and it also translates the 608 data into 708.
     *
     * @var EmbeddedConvert608To708::*|null
     */
    private $convert608To708;

    /**
     * Specifies the 608/708 channel number within the video track from which to extract captions. Unused for passthrough.
     *
     * @var int|null
     */
    private $source608ChannelNumber;

    /**
     * Specifies the video track index used for extracting captions. The system only supports one input video track, so this
     * should always be set to '1'.
     *
     * @var int|null
     */
    private $source608TrackNumber;

    /**
     * By default, the service terminates any unterminated captions at the end of each input. If you want the caption to
     * continue onto your next input, disable this setting.
     *
     * @var EmbeddedTerminateCaptions::*|null
     */
    private $terminateCaptions;

    /**
     * @param array{
     *   Convert608To708?: EmbeddedConvert608To708::*|null,
     *   Source608ChannelNumber?: int|null,
     *   Source608TrackNumber?: int|null,
     *   TerminateCaptions?: EmbeddedTerminateCaptions::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->convert608To708 = $input['Convert608To708'] ?? null;
        $this->source608ChannelNumber = $input['Source608ChannelNumber'] ?? null;
        $this->source608TrackNumber = $input['Source608TrackNumber'] ?? null;
        $this->terminateCaptions = $input['TerminateCaptions'] ?? null;
    }

    /**
     * @param array{
     *   Convert608To708?: EmbeddedConvert608To708::*|null,
     *   Source608ChannelNumber?: int|null,
     *   Source608TrackNumber?: int|null,
     *   TerminateCaptions?: EmbeddedTerminateCaptions::*|null,
     * }|EmbeddedSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EmbeddedConvert608To708::*|null
     */
    public function getConvert608To708(): ?string
    {
        return $this->convert608To708;
    }

    public function getSource608ChannelNumber(): ?int
    {
        return $this->source608ChannelNumber;
    }

    public function getSource608TrackNumber(): ?int
    {
        return $this->source608TrackNumber;
    }

    /**
     * @return EmbeddedTerminateCaptions::*|null
     */
    public function getTerminateCaptions(): ?string
    {
        return $this->terminateCaptions;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->convert608To708) {
            if (!EmbeddedConvert608To708::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "convert608To708" for "%s". The value "%s" is not a valid "EmbeddedConvert608To708".', __CLASS__, $v));
            }
            $payload['convert608To708'] = $v;
        }
        if (null !== $v = $this->source608ChannelNumber) {
            $payload['source608ChannelNumber'] = $v;
        }
        if (null !== $v = $this->source608TrackNumber) {
            $payload['source608TrackNumber'] = $v;
        }
        if (null !== $v = $this->terminateCaptions) {
            if (!EmbeddedTerminateCaptions::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "terminateCaptions" for "%s". The value "%s" is not a valid "EmbeddedTerminateCaptions".', __CLASS__, $v));
            }
            $payload['terminateCaptions'] = $v;
        }

        return $payload;
    }
}
