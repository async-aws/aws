<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CaptionSourceConvertPaintOnToPopOn;
use AsyncAws\MediaConvert\Enum\FileSourceConvert608To708;
use AsyncAws\MediaConvert\Enum\FileSourceTimeDeltaUnits;

/**
 * If your input captions are SCC, SMI, SRT, STL, TTML, WebVTT, or IMSC 1.1 in an xml file, specify the URI of the input
 * caption source file. If your caption source is IMSC in an IMF package, use TrackSourceSettings instead of
 * FileSoureSettings.
 */
final class FileSourceSettings
{
    /**
     * Specify whether this set of input captions appears in your outputs in both 608 and 708 format. If you choose
     * Upconvert, MediaConvert includes the captions data in two ways: it passes the 608 data through using the 608
     * compatibility bytes fields of the 708 wrapper, and it also translates the 608 data into 708.
     *
     * @var FileSourceConvert608To708::*|null
     */
    private $convert608To708;

    /**
     * Choose the presentation style of your input SCC captions. To use the same presentation style as your input: Keep the
     * default value, Disabled. To convert paint-on captions to pop-on: Choose Enabled. We also recommend that you choose
     * Enabled if you notice additional repeated lines in your output captions.
     *
     * @var CaptionSourceConvertPaintOnToPopOn::*|null
     */
    private $convertPaintToPop;

    /**
     * Ignore this setting unless your input captions format is SCC. To have the service compensate for differing frame
     * rates between your input captions and input video, specify the frame rate of the captions file. Specify this value as
     * a fraction. For example, you might specify 24 / 1 for 24 fps, 25 / 1 for 25 fps, 24000 / 1001 for 23.976 fps, or
     * 30000 / 1001 for 29.97 fps.
     *
     * @var CaptionSourceFramerate|null
     */
    private $framerate;

    /**
     * External caption file used for loading captions. Accepted file extensions are 'scc', 'ttml', 'dfxp', 'stl', 'srt',
     * 'xml', 'smi', 'webvtt', and 'vtt'.
     *
     * @var string|null
     */
    private $sourceFile;

    /**
     * Optional. Use this setting when you need to adjust the sync between your sidecar captions and your video. For more
     * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/time-delta-use-cases.html. Enter a positive or
     * negative number to modify the times in the captions file. For example, type 15 to add 15 seconds to all the times in
     * the captions file. Type -5 to subtract 5 seconds from the times in the captions file. You can optionally specify your
     * time delta in milliseconds instead of seconds. When you do so, set the related setting, Time delta units to
     * Milliseconds. Note that, when you specify a time delta for timecode-based caption sources, such as SCC and STL, and
     * your time delta isn't a multiple of the input frame rate, MediaConvert snaps the captions to the nearest frame. For
     * example, when your input video frame rate is 25 fps and you specify 1010ms for time delta, MediaConvert delays your
     * captions by 1000 ms.
     *
     * @var int|null
     */
    private $timeDelta;

    /**
     * When you use the setting Time delta to adjust the sync between your sidecar captions and your video, use this setting
     * to specify the units for the delta that you specify. When you don't specify a value for Time delta units,
     * MediaConvert uses seconds by default.
     *
     * @var FileSourceTimeDeltaUnits::*|null
     */
    private $timeDeltaUnits;

    /**
     * @param array{
     *   Convert608To708?: null|FileSourceConvert608To708::*,
     *   ConvertPaintToPop?: null|CaptionSourceConvertPaintOnToPopOn::*,
     *   Framerate?: null|CaptionSourceFramerate|array,
     *   SourceFile?: null|string,
     *   TimeDelta?: null|int,
     *   TimeDeltaUnits?: null|FileSourceTimeDeltaUnits::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->convert608To708 = $input['Convert608To708'] ?? null;
        $this->convertPaintToPop = $input['ConvertPaintToPop'] ?? null;
        $this->framerate = isset($input['Framerate']) ? CaptionSourceFramerate::create($input['Framerate']) : null;
        $this->sourceFile = $input['SourceFile'] ?? null;
        $this->timeDelta = $input['TimeDelta'] ?? null;
        $this->timeDeltaUnits = $input['TimeDeltaUnits'] ?? null;
    }

    /**
     * @param array{
     *   Convert608To708?: null|FileSourceConvert608To708::*,
     *   ConvertPaintToPop?: null|CaptionSourceConvertPaintOnToPopOn::*,
     *   Framerate?: null|CaptionSourceFramerate|array,
     *   SourceFile?: null|string,
     *   TimeDelta?: null|int,
     *   TimeDeltaUnits?: null|FileSourceTimeDeltaUnits::*,
     * }|FileSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FileSourceConvert608To708::*|null
     */
    public function getConvert608To708(): ?string
    {
        return $this->convert608To708;
    }

    /**
     * @return CaptionSourceConvertPaintOnToPopOn::*|null
     */
    public function getConvertPaintToPop(): ?string
    {
        return $this->convertPaintToPop;
    }

    public function getFramerate(): ?CaptionSourceFramerate
    {
        return $this->framerate;
    }

    public function getSourceFile(): ?string
    {
        return $this->sourceFile;
    }

    public function getTimeDelta(): ?int
    {
        return $this->timeDelta;
    }

    /**
     * @return FileSourceTimeDeltaUnits::*|null
     */
    public function getTimeDeltaUnits(): ?string
    {
        return $this->timeDeltaUnits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->convert608To708) {
            if (!FileSourceConvert608To708::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "convert608To708" for "%s". The value "%s" is not a valid "FileSourceConvert608To708".', __CLASS__, $v));
            }
            $payload['convert608To708'] = $v;
        }
        if (null !== $v = $this->convertPaintToPop) {
            if (!CaptionSourceConvertPaintOnToPopOn::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "convertPaintToPop" for "%s". The value "%s" is not a valid "CaptionSourceConvertPaintOnToPopOn".', __CLASS__, $v));
            }
            $payload['convertPaintToPop'] = $v;
        }
        if (null !== $v = $this->framerate) {
            $payload['framerate'] = $v->requestBody();
        }
        if (null !== $v = $this->sourceFile) {
            $payload['sourceFile'] = $v;
        }
        if (null !== $v = $this->timeDelta) {
            $payload['timeDelta'] = $v;
        }
        if (null !== $v = $this->timeDeltaUnits) {
            if (!FileSourceTimeDeltaUnits::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timeDeltaUnits" for "%s". The value "%s" is not a valid "FileSourceTimeDeltaUnits".', __CLASS__, $v));
            }
            $payload['timeDeltaUnits'] = $v;
        }

        return $payload;
    }
}
