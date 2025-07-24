<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AncillaryConvert608To708;
use AsyncAws\MediaConvert\Enum\AncillaryTerminateCaptions;

/**
 * Settings for ancillary captions source.
 */
final class AncillarySourceSettings
{
    /**
     * Specify whether this set of input captions appears in your outputs in both 608 and 708 format. If you choose
     * Upconvert, MediaConvert includes the captions data in two ways: it passes the 608 data through using the 608
     * compatibility bytes fields of the 708 wrapper, and it also translates the 608 data into 708.
     *
     * @var AncillaryConvert608To708::*|string|null
     */
    private $convert608To708;

    /**
     * Specifies the 608 channel number in the ancillary data track from which to extract captions. Unused for passthrough.
     *
     * @var int|null
     */
    private $sourceAncillaryChannelNumber;

    /**
     * By default, the service terminates any unterminated captions at the end of each input. If you want the caption to
     * continue onto your next input, disable this setting.
     *
     * @var AncillaryTerminateCaptions::*|string|null
     */
    private $terminateCaptions;

    /**
     * @param array{
     *   Convert608To708?: null|AncillaryConvert608To708::*|string,
     *   SourceAncillaryChannelNumber?: null|int,
     *   TerminateCaptions?: null|AncillaryTerminateCaptions::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->convert608To708 = $input['Convert608To708'] ?? null;
        $this->sourceAncillaryChannelNumber = $input['SourceAncillaryChannelNumber'] ?? null;
        $this->terminateCaptions = $input['TerminateCaptions'] ?? null;
    }

    /**
     * @param array{
     *   Convert608To708?: null|AncillaryConvert608To708::*|string,
     *   SourceAncillaryChannelNumber?: null|int,
     *   TerminateCaptions?: null|AncillaryTerminateCaptions::*|string,
     * }|AncillarySourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AncillaryConvert608To708::*|string|null
     */
    public function getConvert608To708(): ?string
    {
        return $this->convert608To708;
    }

    public function getSourceAncillaryChannelNumber(): ?int
    {
        return $this->sourceAncillaryChannelNumber;
    }

    /**
     * @return AncillaryTerminateCaptions::*|string|null
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
            if (!AncillaryConvert608To708::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "convert608To708" for "%s". The value "%s" is not a valid "AncillaryConvert608To708".', __CLASS__, $v));
            }
            $payload['convert608To708'] = $v;
        }
        if (null !== $v = $this->sourceAncillaryChannelNumber) {
            $payload['sourceAncillaryChannelNumber'] = $v;
        }
        if (null !== $v = $this->terminateCaptions) {
            if (!AncillaryTerminateCaptions::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "terminateCaptions" for "%s". The value "%s" is not a valid "AncillaryTerminateCaptions".', __CLASS__, $v));
            }
            $payload['terminateCaptions'] = $v;
        }

        return $payload;
    }
}
