<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\DvbddsHandling;
use AsyncAws\MediaConvert\Enum\DvbSubSubtitleFallbackFont;
use AsyncAws\MediaConvert\Enum\DvbSubtitleAlignment;
use AsyncAws\MediaConvert\Enum\DvbSubtitleApplyFontColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleBackgroundColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleFontColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleOutlineColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleShadowColor;
use AsyncAws\MediaConvert\Enum\DvbSubtitleStylePassthrough;
use AsyncAws\MediaConvert\Enum\DvbSubtitleTeletextSpacing;
use AsyncAws\MediaConvert\Enum\DvbSubtitlingType;
use AsyncAws\MediaConvert\Enum\FontScript;

/**
 * Settings related to DVB-Sub captions. Set up DVB-Sub captions in the same output as your video. For more information,
 * see https://docs.aws.amazon.com/mediaconvert/latest/ug/dvb-sub-output-captions.html.
 */
final class DvbSubDestinationSettings
{
    /**
     * Specify the alignment of your captions. If no explicit x_position is provided, setting alignment to centered will
     * placethe captions at the bottom center of the output. Similarly, setting a left alignment willalign captions to the
     * bottom left of the output. If x and y positions are given in conjunction with the alignment parameter, the font will
     * be justified (either left or centered) relative to those coordinates. Within your job settings, all of your DVB-Sub
     * settings must be identical.
     *
     * @var DvbSubtitleAlignment::*|null
     */
    private $alignment;

    /**
     * Ignore this setting unless Style Passthrough is set to Enabled and Font color set to Black, Yellow, Red, Green, Blue,
     * or Hex. Use Apply font color for additional font color controls. When you choose White text only, or leave blank,
     * your font color setting only applies to white text in your input captions. For example, if your font color setting is
     * Yellow, and your input captions have red and white text, your output captions will have red and yellow text. When you
     * choose ALL_TEXT, your font color setting applies to all of your output captions text.
     *
     * @var DvbSubtitleApplyFontColor::*|null
     */
    private $applyFontColor;

    /**
     * Specify the color of the rectangle behind the captions. Leave background color blank and set Style passthrough to
     * enabled to use the background color data from your input captions, if present.
     *
     * @var DvbSubtitleBackgroundColor::*|null
     */
    private $backgroundColor;

    /**
     * Specify the opacity of the background rectangle. Enter a value from 0 to 255, where 0 is transparent and 255 is
     * opaque. If Style passthrough is set to enabled, leave blank to pass through the background style information in your
     * input captions to your output captions. If Style passthrough is set to disabled, leave blank to use a value of 0 and
     * remove all backgrounds from your output captions. Within your job settings, all of your DVB-Sub settings must be
     * identical.
     *
     * @var int|null
     */
    private $backgroundOpacity;

    /**
     * Specify how MediaConvert handles the display definition segment (DDS). To exclude the DDS from this set of captions:
     * Keep the default, None. To include the DDS: Choose Specified. When you do, also specify the offset coordinates of the
     * display window with DDS x-coordinate and DDS y-coordinate. To include the DDS, but not include display window data:
     * Choose No display window. When you do, you can write position metadata to the page composition segment (PCS) with DDS
     * x-coordinate and DDS y-coordinate. For video resolutions with a height of 576 pixels or less, MediaConvert doesn't
     * include the DDS, regardless of the value you choose for DDS handling. All burn-in and DVB-Sub font settings must
     * match. To include the DDS, with optimized subtitle placement and reduced data overhead: We recommend that you choose
     * Specified (optimal). This option provides the same visual positioning as Specified while using less bandwidth. This
     * also supports resolutions higher than 1080p while maintaining full DVB-Sub compatibility. When you do, also specify
     * the offset coordinates of the display window with DDS x-coordinate and DDS y-coordinate.
     *
     * @var DvbddsHandling::*|null
     */
    private $ddsHandling;

    /**
     * Use this setting, along with DDS y-coordinate, to specify the upper left corner of the display definition segment
     * (DDS) display window. With this setting, specify the distance, in pixels, between the left side of the frame and the
     * left side of the DDS display window. Keep the default value, 0, to have MediaConvert automatically choose this
     * offset. Related setting: When you use this setting, you must set DDS handling to a value other than None.
     * MediaConvert uses these values to determine whether to write page position data to the DDS or to the page composition
     * segment. All burn-in and DVB-Sub font settings must match.
     *
     * @var int|null
     */
    private $ddsXcoordinate;

    /**
     * Use this setting, along with DDS x-coordinate, to specify the upper left corner of the display definition segment
     * (DDS) display window. With this setting, specify the distance, in pixels, between the top of the frame and the top of
     * the DDS display window. Keep the default value, 0, to have MediaConvert automatically choose this offset. Related
     * setting: When you use this setting, you must set DDS handling to a value other than None. MediaConvert uses these
     * values to determine whether to write page position data to the DDS or to the page composition segment (PCS). All
     * burn-in and DVB-Sub font settings must match.
     *
     * @var int|null
     */
    private $ddsYcoordinate;

    /**
     * Specify the font that you want the service to use for your burn in captions when your input captions specify a font
     * that MediaConvert doesn't support. When you set Fallback font to best match, or leave blank, MediaConvert uses a
     * supported font that most closely matches the font that your input captions specify. When there are multiple
     * unsupported fonts in your input captions, MediaConvert matches each font with the supported font that matches best.
     * When you explicitly choose a replacement font, MediaConvert uses that font to replace all unsupported fonts from your
     * input.
     *
     * @var DvbSubSubtitleFallbackFont::*|null
     */
    private $fallbackFont;

    /**
     * Specify the color of the captions text. Leave Font color blank and set Style passthrough to enabled to use the font
     * color data from your input captions, if present. Within your job settings, all of your DVB-Sub settings must be
     * identical.
     *
     * @var DvbSubtitleFontColor::*|null
     */
    private $fontColor;

    /**
     * Specify a bold TrueType font file to use when rendering your output captions. Enter an S3, HTTP, or HTTPS URL. When
     * you do, you must also separately specify a regular, an italic, and a bold italic font file.
     *
     * @var string|null
     */
    private $fontFileBold;

    /**
     * Specify a bold italic TrueType font file to use when rendering your output captions.
     * Enter an S3, HTTP, or HTTPS URL.
     * When you do, you must also separately specify a regular, a bold, and an italic font file.
     *
     * @var string|null
     */
    private $fontFileBoldItalic;

    /**
     * Specify an italic TrueType font file to use when rendering your output captions. Enter an S3, HTTP, or HTTPS URL.
     * When you do, you must also separately specify a regular, a bold, and a bold italic font file.
     *
     * @var string|null
     */
    private $fontFileItalic;

    /**
     * Specify a regular TrueType font file to use when rendering your output captions. Enter an S3, HTTP, or HTTPS URL.
     * When you do, you must also separately specify a bold, an italic, and a bold italic font file.
     *
     * @var string|null
     */
    private $fontFileRegular;

    /**
     * Specify the opacity of the burned-in captions. 255 is opaque; 0 is transparent.
     * Within your job settings, all of your DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $fontOpacity;

    /**
     * Specify the Font resolution in DPI (dots per inch).
     * Within your job settings, all of your DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $fontResolution;

    /**
     * Set Font script to Automatically determined, or leave blank, to automatically determine the font script in your input
     * captions. Otherwise, set to Simplified Chinese (HANS) or Traditional Chinese (HANT) if your input font script uses
     * Simplified or Traditional Chinese. Within your job settings, all of your DVB-Sub settings must be identical.
     *
     * @var FontScript::*|null
     */
    private $fontScript;

    /**
     * Specify the Font size in pixels. Must be a positive integer. Set to 0, or leave blank, for automatic font size.
     * Within your job settings, all of your DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $fontSize;

    /**
     * Specify the height, in pixels, of this set of DVB-Sub captions. The default value is 576 pixels. Related setting:
     * When you use this setting, you must set DDS handling to a value other than None. All burn-in and DVB-Sub font
     * settings must match.
     *
     * @var int|null
     */
    private $height;

    /**
     * Ignore this setting unless your Font color is set to Hex. Enter either six or eight hexidecimal digits, representing
     * red, green, and blue, with two optional extra digits for alpha. For example a value of 1122AABB is a red value of
     * 0x11, a green value of 0x22, a blue value of 0xAA, and an alpha value of 0xBB.
     *
     * @var string|null
     */
    private $hexFontColor;

    /**
     * Specify font outline color. Leave Outline color blank and set Style passthrough to enabled to use the font outline
     * color data from your input captions, if present. Within your job settings, all of your DVB-Sub settings must be
     * identical.
     *
     * @var DvbSubtitleOutlineColor::*|null
     */
    private $outlineColor;

    /**
     * Specify the Outline size of the caption text, in pixels. Leave Outline size blank and set Style passthrough to
     * enabled to use the outline size data from your input captions, if present. Within your job settings, all of your
     * DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $outlineSize;

    /**
     * Specify the color of the shadow cast by the captions. Leave Shadow color blank and set Style passthrough to enabled
     * to use the shadow color data from your input captions, if present. Within your job settings, all of your DVB-Sub
     * settings must be identical.
     *
     * @var DvbSubtitleShadowColor::*|null
     */
    private $shadowColor;

    /**
     * Specify the opacity of the shadow. Enter a value from 0 to 255, where 0 is transparent and 255 is opaque. If Style
     * passthrough is set to Enabled, leave Shadow opacity blank to pass through the shadow style information in your input
     * captions to your output captions. If Style passthrough is set to disabled, leave blank to use a value of 0 and remove
     * all shadows from your output captions. Within your job settings, all of your DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $shadowOpacity;

    /**
     * Specify the horizontal offset of the shadow, relative to the captions in pixels. A value of -2 would result in a
     * shadow offset 2 pixels to the left. Within your job settings, all of your DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $shadowXoffset;

    /**
     * Specify the vertical offset of the shadow relative to the captions in pixels. A value of -2 would result in a shadow
     * offset 2 pixels above the text. Leave Shadow y-offset blank and set Style passthrough to enabled to use the shadow
     * y-offset data from your input captions, if present. Within your job settings, all of your DVB-Sub settings must be
     * identical.
     *
     * @var int|null
     */
    private $shadowYoffset;

    /**
     * To use the available style, color, and position information from your input captions: Set Style passthrough to
     * Enabled. Note that MediaConvert uses default settings for any missing style or position information in your input
     * captions To ignore the style and position information from your input captions and use default settings: Leave blank
     * or keep the default value, Disabled. Default settings include white text with black outlining, bottom-center
     * positioning, and automatic sizing. Whether you set Style passthrough to enabled or not, you can also choose to
     * manually override any of the individual style and position settings. You can also override any fonts by manually
     * specifying custom font files.
     *
     * @var DvbSubtitleStylePassthrough::*|null
     */
    private $stylePassthrough;

    /**
     * Specify whether your DVB subtitles are standard or for hearing impaired. Choose hearing impaired if your subtitles
     * include audio descriptions and dialogue. Choose standard if your subtitles include only dialogue.
     *
     * @var DvbSubtitlingType::*|null
     */
    private $subtitlingType;

    /**
     * Specify whether the Text spacing in your captions is set by the captions grid, or varies depending on letter width.
     * Choose fixed grid to conform to the spacing specified in the captions file more accurately. Choose proportional to
     * make the text easier to read for closed captions. Within your job settings, all of your DVB-Sub settings must be
     * identical.
     *
     * @var DvbSubtitleTeletextSpacing::*|null
     */
    private $teletextSpacing;

    /**
     * Specify the width, in pixels, of this set of DVB-Sub captions. The default value is 720 pixels. Related setting: When
     * you use this setting, you must set DDS handling to a value other than None. All burn-in and DVB-Sub font settings
     * must match.
     *
     * @var int|null
     */
    private $width;

    /**
     * Specify the horizontal position of the captions, relative to the left side of the output in pixels. A value of 10
     * would result in the captions starting 10 pixels from the left of the output. If no explicit x_position is provided,
     * the horizontal caption position will be determined by the alignment parameter. Within your job settings, all of your
     * DVB-Sub settings must be identical.
     *
     * @var int|null
     */
    private $xposition;

    /**
     * Specify the vertical position of the captions, relative to the top of the output in pixels. A value of 10 would
     * result in the captions starting 10 pixels from the top of the output. If no explicit y_position is provided, the
     * caption will be positioned towards the bottom of the output. Within your job settings, all of your DVB-Sub settings
     * must be identical.
     *
     * @var int|null
     */
    private $yposition;

    /**
     * @param array{
     *   Alignment?: null|DvbSubtitleAlignment::*,
     *   ApplyFontColor?: null|DvbSubtitleApplyFontColor::*,
     *   BackgroundColor?: null|DvbSubtitleBackgroundColor::*,
     *   BackgroundOpacity?: null|int,
     *   DdsHandling?: null|DvbddsHandling::*,
     *   DdsXCoordinate?: null|int,
     *   DdsYCoordinate?: null|int,
     *   FallbackFont?: null|DvbSubSubtitleFallbackFont::*,
     *   FontColor?: null|DvbSubtitleFontColor::*,
     *   FontFileBold?: null|string,
     *   FontFileBoldItalic?: null|string,
     *   FontFileItalic?: null|string,
     *   FontFileRegular?: null|string,
     *   FontOpacity?: null|int,
     *   FontResolution?: null|int,
     *   FontScript?: null|FontScript::*,
     *   FontSize?: null|int,
     *   Height?: null|int,
     *   HexFontColor?: null|string,
     *   OutlineColor?: null|DvbSubtitleOutlineColor::*,
     *   OutlineSize?: null|int,
     *   ShadowColor?: null|DvbSubtitleShadowColor::*,
     *   ShadowOpacity?: null|int,
     *   ShadowXOffset?: null|int,
     *   ShadowYOffset?: null|int,
     *   StylePassthrough?: null|DvbSubtitleStylePassthrough::*,
     *   SubtitlingType?: null|DvbSubtitlingType::*,
     *   TeletextSpacing?: null|DvbSubtitleTeletextSpacing::*,
     *   Width?: null|int,
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
        $this->ddsHandling = $input['DdsHandling'] ?? null;
        $this->ddsXcoordinate = $input['DdsXCoordinate'] ?? null;
        $this->ddsYcoordinate = $input['DdsYCoordinate'] ?? null;
        $this->fallbackFont = $input['FallbackFont'] ?? null;
        $this->fontColor = $input['FontColor'] ?? null;
        $this->fontFileBold = $input['FontFileBold'] ?? null;
        $this->fontFileBoldItalic = $input['FontFileBoldItalic'] ?? null;
        $this->fontFileItalic = $input['FontFileItalic'] ?? null;
        $this->fontFileRegular = $input['FontFileRegular'] ?? null;
        $this->fontOpacity = $input['FontOpacity'] ?? null;
        $this->fontResolution = $input['FontResolution'] ?? null;
        $this->fontScript = $input['FontScript'] ?? null;
        $this->fontSize = $input['FontSize'] ?? null;
        $this->height = $input['Height'] ?? null;
        $this->hexFontColor = $input['HexFontColor'] ?? null;
        $this->outlineColor = $input['OutlineColor'] ?? null;
        $this->outlineSize = $input['OutlineSize'] ?? null;
        $this->shadowColor = $input['ShadowColor'] ?? null;
        $this->shadowOpacity = $input['ShadowOpacity'] ?? null;
        $this->shadowXoffset = $input['ShadowXOffset'] ?? null;
        $this->shadowYoffset = $input['ShadowYOffset'] ?? null;
        $this->stylePassthrough = $input['StylePassthrough'] ?? null;
        $this->subtitlingType = $input['SubtitlingType'] ?? null;
        $this->teletextSpacing = $input['TeletextSpacing'] ?? null;
        $this->width = $input['Width'] ?? null;
        $this->xposition = $input['XPosition'] ?? null;
        $this->yposition = $input['YPosition'] ?? null;
    }

    /**
     * @param array{
     *   Alignment?: null|DvbSubtitleAlignment::*,
     *   ApplyFontColor?: null|DvbSubtitleApplyFontColor::*,
     *   BackgroundColor?: null|DvbSubtitleBackgroundColor::*,
     *   BackgroundOpacity?: null|int,
     *   DdsHandling?: null|DvbddsHandling::*,
     *   DdsXCoordinate?: null|int,
     *   DdsYCoordinate?: null|int,
     *   FallbackFont?: null|DvbSubSubtitleFallbackFont::*,
     *   FontColor?: null|DvbSubtitleFontColor::*,
     *   FontFileBold?: null|string,
     *   FontFileBoldItalic?: null|string,
     *   FontFileItalic?: null|string,
     *   FontFileRegular?: null|string,
     *   FontOpacity?: null|int,
     *   FontResolution?: null|int,
     *   FontScript?: null|FontScript::*,
     *   FontSize?: null|int,
     *   Height?: null|int,
     *   HexFontColor?: null|string,
     *   OutlineColor?: null|DvbSubtitleOutlineColor::*,
     *   OutlineSize?: null|int,
     *   ShadowColor?: null|DvbSubtitleShadowColor::*,
     *   ShadowOpacity?: null|int,
     *   ShadowXOffset?: null|int,
     *   ShadowYOffset?: null|int,
     *   StylePassthrough?: null|DvbSubtitleStylePassthrough::*,
     *   SubtitlingType?: null|DvbSubtitlingType::*,
     *   TeletextSpacing?: null|DvbSubtitleTeletextSpacing::*,
     *   Width?: null|int,
     *   XPosition?: null|int,
     *   YPosition?: null|int,
     * }|DvbSubDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DvbSubtitleAlignment::*|null
     */
    public function getAlignment(): ?string
    {
        return $this->alignment;
    }

    /**
     * @return DvbSubtitleApplyFontColor::*|null
     */
    public function getApplyFontColor(): ?string
    {
        return $this->applyFontColor;
    }

    /**
     * @return DvbSubtitleBackgroundColor::*|null
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
     * @return DvbddsHandling::*|null
     */
    public function getDdsHandling(): ?string
    {
        return $this->ddsHandling;
    }

    public function getDdsXcoordinate(): ?int
    {
        return $this->ddsXcoordinate;
    }

    public function getDdsYcoordinate(): ?int
    {
        return $this->ddsYcoordinate;
    }

    /**
     * @return DvbSubSubtitleFallbackFont::*|null
     */
    public function getFallbackFont(): ?string
    {
        return $this->fallbackFont;
    }

    /**
     * @return DvbSubtitleFontColor::*|null
     */
    public function getFontColor(): ?string
    {
        return $this->fontColor;
    }

    public function getFontFileBold(): ?string
    {
        return $this->fontFileBold;
    }

    public function getFontFileBoldItalic(): ?string
    {
        return $this->fontFileBoldItalic;
    }

    public function getFontFileItalic(): ?string
    {
        return $this->fontFileItalic;
    }

    public function getFontFileRegular(): ?string
    {
        return $this->fontFileRegular;
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

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getHexFontColor(): ?string
    {
        return $this->hexFontColor;
    }

    /**
     * @return DvbSubtitleOutlineColor::*|null
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
     * @return DvbSubtitleShadowColor::*|null
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
     * @return DvbSubtitleStylePassthrough::*|null
     */
    public function getStylePassthrough(): ?string
    {
        return $this->stylePassthrough;
    }

    /**
     * @return DvbSubtitlingType::*|null
     */
    public function getSubtitlingType(): ?string
    {
        return $this->subtitlingType;
    }

    /**
     * @return DvbSubtitleTeletextSpacing::*|null
     */
    public function getTeletextSpacing(): ?string
    {
        return $this->teletextSpacing;
    }

    public function getWidth(): ?int
    {
        return $this->width;
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
            if (!DvbSubtitleAlignment::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "alignment" for "%s". The value "%s" is not a valid "DvbSubtitleAlignment".', __CLASS__, $v));
            }
            $payload['alignment'] = $v;
        }
        if (null !== $v = $this->applyFontColor) {
            if (!DvbSubtitleApplyFontColor::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "applyFontColor" for "%s". The value "%s" is not a valid "DvbSubtitleApplyFontColor".', __CLASS__, $v));
            }
            $payload['applyFontColor'] = $v;
        }
        if (null !== $v = $this->backgroundColor) {
            if (!DvbSubtitleBackgroundColor::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "backgroundColor" for "%s". The value "%s" is not a valid "DvbSubtitleBackgroundColor".', __CLASS__, $v));
            }
            $payload['backgroundColor'] = $v;
        }
        if (null !== $v = $this->backgroundOpacity) {
            $payload['backgroundOpacity'] = $v;
        }
        if (null !== $v = $this->ddsHandling) {
            if (!DvbddsHandling::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ddsHandling" for "%s". The value "%s" is not a valid "DvbddsHandling".', __CLASS__, $v));
            }
            $payload['ddsHandling'] = $v;
        }
        if (null !== $v = $this->ddsXcoordinate) {
            $payload['ddsXCoordinate'] = $v;
        }
        if (null !== $v = $this->ddsYcoordinate) {
            $payload['ddsYCoordinate'] = $v;
        }
        if (null !== $v = $this->fallbackFont) {
            if (!DvbSubSubtitleFallbackFont::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "fallbackFont" for "%s". The value "%s" is not a valid "DvbSubSubtitleFallbackFont".', __CLASS__, $v));
            }
            $payload['fallbackFont'] = $v;
        }
        if (null !== $v = $this->fontColor) {
            if (!DvbSubtitleFontColor::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "fontColor" for "%s". The value "%s" is not a valid "DvbSubtitleFontColor".', __CLASS__, $v));
            }
            $payload['fontColor'] = $v;
        }
        if (null !== $v = $this->fontFileBold) {
            $payload['fontFileBold'] = $v;
        }
        if (null !== $v = $this->fontFileBoldItalic) {
            $payload['fontFileBoldItalic'] = $v;
        }
        if (null !== $v = $this->fontFileItalic) {
            $payload['fontFileItalic'] = $v;
        }
        if (null !== $v = $this->fontFileRegular) {
            $payload['fontFileRegular'] = $v;
        }
        if (null !== $v = $this->fontOpacity) {
            $payload['fontOpacity'] = $v;
        }
        if (null !== $v = $this->fontResolution) {
            $payload['fontResolution'] = $v;
        }
        if (null !== $v = $this->fontScript) {
            if (!FontScript::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "fontScript" for "%s". The value "%s" is not a valid "FontScript".', __CLASS__, $v));
            }
            $payload['fontScript'] = $v;
        }
        if (null !== $v = $this->fontSize) {
            $payload['fontSize'] = $v;
        }
        if (null !== $v = $this->height) {
            $payload['height'] = $v;
        }
        if (null !== $v = $this->hexFontColor) {
            $payload['hexFontColor'] = $v;
        }
        if (null !== $v = $this->outlineColor) {
            if (!DvbSubtitleOutlineColor::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "outlineColor" for "%s". The value "%s" is not a valid "DvbSubtitleOutlineColor".', __CLASS__, $v));
            }
            $payload['outlineColor'] = $v;
        }
        if (null !== $v = $this->outlineSize) {
            $payload['outlineSize'] = $v;
        }
        if (null !== $v = $this->shadowColor) {
            if (!DvbSubtitleShadowColor::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "shadowColor" for "%s". The value "%s" is not a valid "DvbSubtitleShadowColor".', __CLASS__, $v));
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
            if (!DvbSubtitleStylePassthrough::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "stylePassthrough" for "%s". The value "%s" is not a valid "DvbSubtitleStylePassthrough".', __CLASS__, $v));
            }
            $payload['stylePassthrough'] = $v;
        }
        if (null !== $v = $this->subtitlingType) {
            if (!DvbSubtitlingType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "subtitlingType" for "%s". The value "%s" is not a valid "DvbSubtitlingType".', __CLASS__, $v));
            }
            $payload['subtitlingType'] = $v;
        }
        if (null !== $v = $this->teletextSpacing) {
            if (!DvbSubtitleTeletextSpacing::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "teletextSpacing" for "%s". The value "%s" is not a valid "DvbSubtitleTeletextSpacing".', __CLASS__, $v));
            }
            $payload['teletextSpacing'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
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
