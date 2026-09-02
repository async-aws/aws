<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\XavcHdIntraCbgProfileClass;
use AsyncAws\MediaConvert\Enum\XavcInterlaceMode;

/**
 * Required when you set Profile to the value XAVC_HD_INTRA_CBG.
 */
final class XavcHdIntraCbgProfileSettings
{
    /**
     * Choose the scan line type for the output. Keep the default value, Progressive to create a progressive output,
     * regardless of the scan type of your input. Use Top field first or Bottom field first to create an output that's
     * interlaced with the same field polarity throughout. Use Follow, default top or Follow, default bottom to produce
     * outputs with the same field polarity as the source. For jobs that have multiple inputs, the output field polarity
     * might change over the course of the output. Follow behavior depends on the input scan type. If the source is
     * interlaced, the output will be interlaced with the same polarity as the source. If the source is progressive, the
     * output will be interlaced with top field bottom field first, depending on which of the Follow options you choose.
     *
     * @var XavcInterlaceMode::*|null
     */
    private $interlaceMode;

    /**
     * Specify the XAVC Intra HD (CBG) Class to set the bitrate of your output. Outputs of the same class have similar image
     * quality over the operating points that are valid for that class.
     *
     * @var XavcHdIntraCbgProfileClass::*|null
     */
    private $xavcClass;

    /**
     * @param array{
     *   InterlaceMode?: XavcInterlaceMode::*|null,
     *   XavcClass?: XavcHdIntraCbgProfileClass::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->interlaceMode = $input['InterlaceMode'] ?? null;
        $this->xavcClass = $input['XavcClass'] ?? null;
    }

    /**
     * @param array{
     *   InterlaceMode?: XavcInterlaceMode::*|null,
     *   XavcClass?: XavcHdIntraCbgProfileClass::*|null,
     * }|XavcHdIntraCbgProfileSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return XavcInterlaceMode::*|null
     */
    public function getInterlaceMode(): ?string
    {
        return $this->interlaceMode;
    }

    /**
     * @return XavcHdIntraCbgProfileClass::*|null
     */
    public function getXavcClass(): ?string
    {
        return $this->xavcClass;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->interlaceMode) {
            if (!XavcInterlaceMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "interlaceMode" for "%s". The value "%s" is not a valid "XavcInterlaceMode".', __CLASS__, $v));
            }
            $payload['interlaceMode'] = $v;
        }
        if (null !== $v = $this->xavcClass) {
            if (!XavcHdIntraCbgProfileClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "xavcClass" for "%s". The value "%s" is not a valid "XavcHdIntraCbgProfileClass".', __CLASS__, $v));
            }
            $payload['xavcClass'] = $v;
        }

        return $payload;
    }
}
