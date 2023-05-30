<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\BurninSubtitleAlignment;
use AsyncAws\MediaConvert\Enum\BurninSubtitleApplyFontColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleBackgroundColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleFallbackFont;
use AsyncAws\MediaConvert\Enum\BurninSubtitleFontColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleOutlineColor;
use AsyncAws\MediaConvert\Enum\BurninSubtitleShadowColor;
use AsyncAws\MediaConvert\Enum\BurnInSubtitleStylePassthrough;
use AsyncAws\MediaConvert\Enum\BurninSubtitleTeletextSpacing;
use AsyncAws\MediaConvert\Enum\FontScript;

/**
 * Burn-in is a captions delivery method, rather than a captions format. Burn-in writes the captions directly on your
 * video frames, replacing pixels of video content with the captions. Set up burn-in captions in the same output as your
 * video. For more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/burn-in-output-captions.html.
 * When you work directly in your JSON job specification, include this object and any required children when you set
 * destinationType to BURN_IN.
 */
final class BurninDestinationSettings
{
    /**
     * Specify the alignment of your captions. If no explicit x_position is provided, setting alignment to centered will
     * placethe captions at the bottom center of the output. Similarly, setting a left alignment willalign captions to the
     * bottom left of the output. If x and y positions are given in conjunction with the alignment parameter, the font will
     * be justified (either left or centered) relative to those coordinates.
     */
    private $alignment;

    /**
     * Ignore this setting unless Style passthrough (StylePassthrough) is set to Enabled and Font color (FontColor) set to
     * Black, Yellow, Red, Green, Blue, or Hex. Use Apply font color (ApplyFontColor) for additional font color controls.
     * When you choose White text only (WHITE_TEXT_ONLY), or leave blank, your font color setting only applies to white text
     * in your input captions. For example, if your font color setting is Yellow, and your input captions have red and white
     * text, your output captions will have red and yellow text. When you choose ALL_TEXT, your font color setting applies
     * to all of your output captions text.
     */
    private $applyFontColor;

    /**
     * Specify the color of the rectangle behind the captions. Leave background color (BackgroundColor) blank and set Style
     * passthrough (StylePassthrough) to enabled to use the background color data from your input captions, if present.
     */
    private $backgroundColor;

    /**
     * Specify the opacity of the background rectangle. Enter a value from 0 to 255, where 0 is transparent and 255 is
     * opaque. If Style passthrough (StylePassthrough) is set to enabled, leave blank to pass through the background style
     * information in your input captions to your output captions. If Style passthrough is set to disabled, leave blank to
     * use a value of 0 and remove all backgrounds from your output captions.
     */
    private $backgroundOpacity;

    /**
     * Specify the font that you want the service to use for your burn in captions when your input captions specify a font
     * that MediaConvert doesn't support. When you set Fallback font (FallbackFont) to best match (BEST_MATCH), or leave
     * blank, MediaConvert uses a supported font that most closely matches the font that your input captions specify. When
     * there are multiple unsupported fonts in your input captions, MediaConvert matches each font with the supported font
     * that matches best. When you explicitly choose a replacement font, MediaConvert uses that font to replace all
     * unsupported fonts from your input.
     */
    private $fallbackFont;

    /**
     * Specify the color of the burned-in captions text. Leave Font color (FontColor) blank and set Style passthrough
     * (StylePassthrough) to enabled to use the font color data from your input captions, if present.
     */
    private $fontColor;

    /**
     * Specify the opacity of the burned-in captions. 255 is opaque; 0 is transparent.
     */
    private $fontOpacity;

    /**
     * Specify the Font resolution (FontResolution) in DPI (dots per inch).
     */
    private $fontResolution;

    /**
     * Set Font script (FontScript) to Automatically determined (AUTOMATIC), or leave blank, to automatically determine the
     * font script in your input captions. Otherwise, set to Simplified Chinese (HANS) or Traditional Chinese (HANT) if your
     * input font script uses Simplified or Traditional Chinese.
     */
    private $fontScript;

    /**
     * Specify the Font size (FontSize) in pixels. Must be a positive integer. Set to 0, or leave blank, for automatic font
     * size.
     */
    private $fontSize;

    /**
     * Ignore this setting unless your Font color is set to Hex. Enter either six or eight hexidecimal digits, representing
     * red, green, and blue, with two optional extra digits for alpha. For example a value of 1122AABB is a red value of
     * 0x11, a green value of 0x22, a blue value of 0xAA, and an alpha value of 0xBB.
     */
    private $hexFontColor;

    /**
     * Specify font outline color. Leave Outline color (OutlineColor) blank and set Style passthrough (StylePassthrough) to
     * enabled to use the font outline color data from your input captions, if present.
     */
    private $outlineColor;

    /**
     * Specify the Outline size (OutlineSize) of the caption text, in pixels. Leave Outline size blank and set Style
     * passthrough (StylePassthrough) to enabled to use the outline size data from your input captions, if present.
     */
    private $outlineSize;

    /**
     * Specify the color of the shadow cast by the captions. Leave Shadow color (ShadowColor) blank and set Style
     * passthrough (StylePassthrough) to enabled to use the shadow color data from your input captions, if present.
     */
    private $shadowColor;

    /**
     * Specify the opacity of the shadow. Enter a value from 0 to 255, where 0 is transparent and 255 is opaque. If Style
     * passthrough (StylePassthrough) is set to Enabled, leave Shadow opacity (ShadowOpacity) blank to pass through the
     * shadow style information in your input captions to your output captions. If Style passthrough is set to disabled,
     * leave blank to use a value of 0 and remove all shadows from your output captions.
     */
    private $shadowOpacity;

    /**
     * Specify the horizontal offset of the shadow, relative to the captions in pixels. A value of -2 would result in a
     * shadow offset 2 pixels to the left.
     */
    private $shadowXoffset;

    /**
     * Specify the vertical offset of the shadow relative to the captions in pixels. A value of -2 would result in a shadow
     * offset 2 pixels above the text. Leave Shadow y-offset (ShadowYOffset) blank and set Style passthrough
     * (StylePassthrough) to enabled to use the shadow y-offset data from your input captions, if present.
     */
    private $shadowYoffset;

    /**
     * Set Style passthrough (StylePassthrough) to ENABLED to use the available style, color, and position information from
     * your input captions. MediaConvert uses default settings for any missing style and position information in your input
     * captions. Set Style passthrough to DISABLED, or leave blank, to ignore the style and position information from your
     * input captions and use default settings: white text with black outlining, bottom-center positioning, and automatic
     * sizing. Whether you set Style passthrough to enabled or not, you can also choose to manually override any of the
     * individual style and position settings.
     */
    private $stylePassthrough;

    /**
     * Specify whether the text spacing (TeletextSpacing) in your captions is set by the captions grid, or varies depending
     * on letter width. Choose fixed grid (FIXED_GRID) to conform to the spacing specified in the captions file more
     * accurately. Choose proportional (PROPORTIONAL) to make the text easier to read for closed captions.
     */
    private $teletextSpacing;

    /**
     * Specify the horizontal position (XPosition) of the captions, relative to the left side of the output in pixels. A
     * value of 10 would result in the captions starting 10 pixels from the left of the output. If no explicit x_position is
     * provided, the horizontal caption position will be determined by the alignment parameter.
     */
    private $xposition;

    /**
     * Specify the vertical position (YPosition) of the captions, relative to the top of the output in pixels. A value of 10
     * would result in the captions starting 10 pixels from the top of the output. If no explicit y_position is provided,
     * the caption will be positioned towards the bottom of the output.
     */
    private $yposition;

    /**
     * @param array{
     *   Alignment?: null|BurninSubtitleAlignment::*,
     *   ApplyFontColor?: null|BurninSubtitleApplyFontColor::*,
     *   BackgroundColor?: null|BurninSubtitleBackgroundColor::*,
     *   BackgroundOpacity?: null|int,
     *   FallbackFont?: null|BurninSubtitleFallbackFont::*,
     *   FontColor?: null|BurninSubtitleFontColor::*,
     *   FontOpacity?: null|int,
     *   FontResolution?: null|int,
     *   FontScript?: null|FontScript::*,
     *   FontSize?: null|int,
     *   HexFontColor?: null|string,
     *   OutlineColor?: null|BurninSubtitleOutlineColor::*,
     *   OutlineSize?: null|int,
     *   ShadowColor?: null|BurninSubtitleShadowColor::*,
     *   ShadowOpacity?: null|int,
     *   ShadowXOffset?: null|int,
     *   ShadowYOffset?: null|int,
     *   StylePassthrough?: null|BurnInSubtitleStylePassthrough::*,
     *   TeletextSpacing?: null|BurninSubtitleTeletextSpacing::*,
     *   XPosition?: null|int,
     *   YPosition?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->alignment = $input['Alignment'] ?? null;
        $this->applyFontColor = $input['ApplyFontColor'] ?? null;
        $this->backgroundColor = $input['BackgroundColor'] ?? null;
        $this->backgroundOpacity = $input['BackgroundOpacity'] ?? null;
        $this->fallbackFont = $input['FallbackFont'] ?? null;
        $this->fontColor = $input['FontColor'] ?? null;
        $this->fontOpacity = $input['FontOpacity'] ?? null;
        $this->fontResolution = $input['FontResolution'] ?? null;
        $this->fontScript = $input['FontScript'] ?? null;
        $this->fontSize = $input['FontSize'] ?? null;
        $this->hexFontColor = $input['HexFontColor'] ?? null;
        $this->outlineColor = $input['OutlineColor'] ?? null;
        $this->outlineSize = $input['OutlineSize'] ?? null;
        $this->shadowColor = $input['ShadowColor'] ?? null;
        $this->shadowOpacity = $input['ShadowOpacity'] ?? null;
        $this->shadowXoffset = $input['ShadowXOffset'] ?? null;
        $this->shadowYoffset = $input['ShadowYOffset'] ?? null;
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
        $this->teletextSpacing = $input['TeletextSpacing'] ?? null;
        $this->xposition = $input['XPosition'] ?? null;
        $this->yposition = $input['YPosition'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BurninSubtitleAlignment::*|null
     */
    public function getAlignment(): ?string
    {
        return $this->alignment;
    }

    /**
     * @return BurninSubtitleApplyFontColor::*|null
     */
    public function getApplyFontColor(): ?string
    {
        return $this->applyFontColor;
    }

    /**
     * @return BurninSubtitleBackgroundColor::*|null
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
     * @return BurninSubtitleFallbackFont::*|null
     */
    public function getFallbackFont(): ?string
    {
        return $this->fallbackFont;
    }

    /**
     * @return BurninSubtitleFontColor::*|null
     */
    public function getFontColor(): ?string
    {
        return $this->fontColor;
    }

    public function getFontOpacity(): ?int
    {
        return $this->fontOpacity;
    }

    public function getFontResolution(): ?int
    {
        return $this->fontResolution;
    }

    /**
     * @return FontScript::*|null
     */
    public function getFontScript(): ?string
    {
        return $this->fontScript;
    }

    public function getFontSize(): ?int
    {
        return $this->fontSize;
    }

    public function getHexFontColor(): ?string
    {
        return $this->hexFontColor;
    }

    /**
     * @return BurninSubtitleOutlineColor::*|null
     */
    public function getOutlineColor(): ?string
    {
        return $this->outlineColor;
    }

    public function getOutlineSize(): ?int
    {
        return $this->outlineSize;
    }

    /**
     * @return BurninSubtitleShadowColor::*|null
     */
    public function getShadowColor(): ?string
    {
        return $this->shadowColor;
    }

    public function getShadowOpacity(): ?int
    {
        return $this->shadowOpacity;
    }

    public function getShadowXoffset(): ?int
    {
        return $this->shadowXoffset;
    }

    public function getShadowYoffset(): ?int
    {
        return $this->shadowYoffset;
    }

    /**
     * @return BurnInSubtitleStylePassthrough::*|null
     */
    public function getStylePassthrough(): ?string
    {
        return $this->stylePassthrough;
    }

    /**
     * @return BurninSubtitleTeletextSpacing::*|null
     */
    public function getTeletextSpacing(): ?string
    {
        return $this->teletextSpacing;
    }

    public function getXposition(): ?int
    {
        return $this->xposition;
    }

    public function getYposition(): ?int
    {
        return $this->yposition;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->alignment) {
            if (!BurninSubtitleAlignment::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "alignment" for "%s". The value "%s" is not a valid "BurninSubtitleAlignment".', __CLASS__, $v));
            }
            $payload['alignment'] = $v;
        }
        if (null !== $v = $this->applyFontColor) {
            if (!BurninSubtitleApplyFontColor::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "applyFontColor" for "%s". The value "%s" is not a valid "BurninSubtitleApplyFontColor".', __CLASS__, $v));
            }
            $payload['applyFontColor'] = $v;
        }
        if (null !== $v = $this->backgroundColor) {
            if (!BurninSubtitleBackgroundColor::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "backgroundColor" for "%s". The value "%s" is not a valid "BurninSubtitleBackgroundColor".', __CLASS__, $v));
            }
            $payload['backgroundColor'] = $v;
        }
        if (null !== $v = $this->backgroundOpacity) {
            $payload['backgroundOpacity'] = $v;
        }
        if (null !== $v = $this->fallbackFont) {
            if (!BurninSubtitleFallbackFont::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "fallbackFont" for "%s". The value "%s" is not a valid "BurninSubtitleFallbackFont".', __CLASS__, $v));
            }
            $payload['fallbackFont'] = $v;
        }
        if (null !== $v = $this->fontColor) {
            if (!BurninSubtitleFontColor::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "fontColor" for "%s". The value "%s" is not a valid "BurninSubtitleFontColor".', __CLASS__, $v));
            }
            $payload['fontColor'] = $v;
        }
        if (null !== $v = $this->fontOpacity) {
            $payload['fontOpacity'] = $v;
        }
        if (null !== $v = $this->fontResolution) {
            $payload['fontResolution'] = $v;
        }
        if (null !== $v = $this->fontScript) {
            if (!FontScript::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "fontScript" for "%s". The value "%s" is not a valid "FontScript".', __CLASS__, $v));
            }
            $payload['fontScript'] = $v;
        }
        if (null !== $v = $this->fontSize) {
            $payload['fontSize'] = $v;
        }
        if (null !== $v = $this->hexFontColor) {
            $payload['hexFontColor'] = $v;
        }
        if (null !== $v = $this->outlineColor) {
            if (!BurninSubtitleOutlineColor::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "outlineColor" for "%s". The value "%s" is not a valid "BurninSubtitleOutlineColor".', __CLASS__, $v));
            }
            $payload['outlineColor'] = $v;
        }
        if (null !== $v = $this->outlineSize) {
            $payload['outlineSize'] = $v;
        }
        if (null !== $v = $this->shadowColor) {
            if (!BurninSubtitleShadowColor::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "shadowColor" for "%s". The value "%s" is not a valid "BurninSubtitleShadowColor".', __CLASS__, $v));
            }
            $payload['shadowColor'] = $v;
        }
        if (null !== $v = $this->shadowOpacity) {
            $payload['shadowOpacity'] = $v;
        }
        if (null !== $v = $this->shadowXoffset) {
            $payload['shadowXOffset'] = $v;
        }
        if (null !== $v = $this->shadowYoffset) {
            $payload['shadowYOffset'] = $v;
        }
        if (null !== $v = $this->stylePassthrough) {
            if (!BurnInSubtitleStylePassthrough::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "BurnInSubtitleStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }
        if (null !== $v = $this->teletextSpacing) {
            if (!BurninSubtitleTeletextSpacing::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "teletextSpacing" for "%s". The value "%s" is not a valid "BurninSubtitleTeletextSpacing".', __CLASS__, $v));
            }
            $payload['teletextSpacing'] = $v;
        }
        if (null !== $v = $this->xposition) {
            $payload['xPosition'] = $v;
        }
        if (null !== $v = $this->yposition) {
            $payload['yPosition'] = $v;
        }

        return $payload;
    }
}
