<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\TtmlBackgroundColor;
use AsyncAws\MediaConvert\Enum\TtmlFontColor;
use AsyncAws\MediaConvert\Enum\TtmlFontStyle;
use AsyncAws\MediaConvert\Enum\TtmlFontWeight;
use AsyncAws\MediaConvert\Enum\TtmlStylePassthrough;
use AsyncAws\MediaConvert\Enum\TtmlTextDecoration;

/**
 * Settings related to TTML captions. TTML is a sidecar format that holds captions in a file that is separate from the
 * video container. Set up sidecar captions in the same output group, but different output from your video. For more
 * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ttml-and-webvtt-output-captions.html.
 */
final class TtmlDestinationSettings
{
    /**
     * Specify the color of the rectangle behind the captions. If Style passthrough is set to enabled, leave blank or set to
     * Auto to pass through the background color from your input captions. If Style passthrough is set to disabled, leave
     * blank or set to Auto to use the default black.
     *
     * @var TtmlBackgroundColor::*|null
     */
    private $backgroundColor;

    /**
     * Specify the opacity of the background rectangle. Enter a value from 0 to 255, where 0 is transparent and 255 is
     * opaque. If Style passthrough is set to enabled, leave blank to pass through the background style information in your
     * input captions to your output captions. If Style passthrough is set to disabled and backgroundColor is set, leave
     * blank to use a value of 255 (opaque).
     *
     * @var int|null
     */
    private $backgroundOpacity;

    /**
     * Specify the color of the captions text. If Style passthrough is set to enabled, leave blank or set to Auto to pass
     * through the font color from your input captions. If Style passthrough is set to disabled, leave blank or set to Auto
     * to use the default white.
     *
     * @var TtmlFontColor::*|null
     */
    private $fontColor;

    /**
     * Specify the opacity of the captions. Enter a value from 0 to 255, where 0 is transparent and 255 is opaque. If Style
     * passthrough is set to enabled, leave blank to pass through the font opacity information in your input captions to
     * your output captions. If Style passthrough is set to disabled and fontColor is set, leave blank to use a value of 255
     * (opaque).
     *
     * @var int|null
     */
    private $fontOpacity;

    /**
     * Specify the Font size in pixels. Must be a positive integer. Set to 0, or leave blank, for automatic font size.
     *
     * @var int|null
     */
    private $fontSize;

    /**
     * Specify the font style of the caption text. If Style passthrough is set to enabled, leave blank to pass through the
     * font style from your input captions. If Style passthrough is set to disabled, leave blank to use the default normal
     * style.
     *
     * @var TtmlFontStyle::*|null
     */
    private $fontStyle;

    /**
     * Specify the font weight of the caption text. If Style passthrough is set to enabled, leave blank to pass through the
     * font weight from your input captions. If Style passthrough is set to disabled, leave blank to use the default normal
     * weight.
     *
     * @var TtmlFontWeight::*|null
     */
    private $fontWeight;

    /**
     * Pass through style and position information from a TTML-like input source (TTML, IMSC, SMPTE-TT) to the TTML output.
     *
     * @var TtmlStylePassthrough::*|null
     */
    private $stylePassthrough;

    /**
     * Specify the text decoration of the caption text. If Style passthrough is set to enabled, leave blank to pass through
     * the text decoration from your input captions. If Style passthrough is set to disabled, leave blank to use the default
     * of none.
     *
     * @var TtmlTextDecoration::*|null
     */
    private $textDecoration;

    /**
     * @param array{
     *   BackgroundColor?: TtmlBackgroundColor::*|null,
     *   BackgroundOpacity?: int|null,
     *   FontColor?: TtmlFontColor::*|null,
     *   FontOpacity?: int|null,
     *   FontSize?: int|null,
     *   FontStyle?: TtmlFontStyle::*|null,
     *   FontWeight?: TtmlFontWeight::*|null,
     *   StylePassthrough?: TtmlStylePassthrough::*|null,
     *   TextDecoration?: TtmlTextDecoration::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->backgroundColor = $input['BackgroundColor'] ?? null;
        $this->backgroundOpacity = $input['BackgroundOpacity'] ?? null;
        $this->fontColor = $input['FontColor'] ?? null;
        $this->fontOpacity = $input['FontOpacity'] ?? null;
        $this->fontSize = $input['FontSize'] ?? null;
        $this->fontStyle = $input['FontStyle'] ?? null;
        $this->fontWeight = $input['FontWeight'] ?? null;
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
        $this->textDecoration = $input['TextDecoration'] ?? null;
    }

    /**
     * @param array{
     *   BackgroundColor?: TtmlBackgroundColor::*|null,
     *   BackgroundOpacity?: int|null,
     *   FontColor?: TtmlFontColor::*|null,
     *   FontOpacity?: int|null,
     *   FontSize?: int|null,
     *   FontStyle?: TtmlFontStyle::*|null,
     *   FontWeight?: TtmlFontWeight::*|null,
     *   StylePassthrough?: TtmlStylePassthrough::*|null,
     *   TextDecoration?: TtmlTextDecoration::*|null,
     * }|TtmlDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return TtmlBackgroundColor::*|null
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function getBackgroundOpacity(): ?int
    {
        return $this->backgroundOpacity;
    }

    /**
     * @return TtmlFontColor::*|null
     */
    public function getFontColor(): ?string
    {
        return $this->fontColor;
    }

    public function getFontOpacity(): ?int
    {
        return $this->fontOpacity;
    }

    public function getFontSize(): ?int
    {
        return $this->fontSize;
    }

    /**
     * @return TtmlFontStyle::*|null
     */
    public function getFontStyle(): ?string
    {
        return $this->fontStyle;
    }

    /**
     * @return TtmlFontWeight::*|null
     */
    public function getFontWeight(): ?string
    {
        return $this->fontWeight;
    }

    /**
     * @return TtmlStylePassthrough::*|null
     */
    public function getStylePassthrough(): ?string
    {
        return $this->stylePassthrough;
    }

    /**
     * @return TtmlTextDecoration::*|null
     */
    public function getTextDecoration(): ?string
    {
        return $this->textDecoration;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->backgroundColor) {
            if (!TtmlBackgroundColor::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "backgroundColor" for "%s". The value "%s" is not a valid "TtmlBackgroundColor".', __CLASS__, $v));
            }
            $payload['backgroundColor'] = $v;
        }
        if (null !== $v = $this->backgroundOpacity) {
            $payload['backgroundOpacity'] = $v;
        }
        if (null !== $v = $this->fontColor) {
            if (!TtmlFontColor::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "fontColor" for "%s". The value "%s" is not a valid "TtmlFontColor".', __CLASS__, $v));
            }
            $payload['fontColor'] = $v;
        }
        if (null !== $v = $this->fontOpacity) {
            $payload['fontOpacity'] = $v;
        }
        if (null !== $v = $this->fontSize) {
            $payload['fontSize'] = $v;
        }
        if (null !== $v = $this->fontStyle) {
            if (!TtmlFontStyle::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "fontStyle" for "%s". The value "%s" is not a valid "TtmlFontStyle".', __CLASS__, $v));
            }
            $payload['fontStyle'] = $v;
        }
        if (null !== $v = $this->fontWeight) {
            if (!TtmlFontWeight::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "fontWeight" for "%s". The value "%s" is not a valid "TtmlFontWeight".', __CLASS__, $v));
            }
            $payload['fontWeight'] = $v;
        }
        if (null !== $v = $this->stylePassthrough) {
            if (!TtmlStylePassthrough::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "TtmlStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }
        if (null !== $v = $this->textDecoration) {
            if (!TtmlTextDecoration::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "textDecoration" for "%s". The value "%s" is not a valid "TtmlTextDecoration".', __CLASS__, $v));
            }
            $payload['textDecoration'] = $v;
        }

        return $payload;
    }
}
