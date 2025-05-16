<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CaptionSourceByteRateLimit;
use AsyncAws\MediaConvert\Enum\CaptionSourceConvertPaintOnToPopOn;
use AsyncAws\MediaConvert\Enum\CaptionSourceUpconvertSTLToTeletext;
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
     * Choose whether to limit the byte rate at which your SCC input captions are inserted into your output. To not limit
     * the caption rate: We recommend that you keep the default value, Disabled. MediaConvert inserts captions in your
     * output according to the byte rates listed in the EIA-608 specification, typically 2 or 3 caption bytes per frame
     * depending on your output frame rate. To limit your output caption rate: Choose Enabled. Choose this option if your
     * downstream systems require a maximum of 2 caption bytes per frame. Note that this setting has no effect when your
     * output frame rate is 30 or 60.
     *
     * @var CaptionSourceByteRateLimit::*|null
     */
    private $byteRateLimit;

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
     * Specify whether this set of input captions appears in your outputs in both STL and Teletext format. If you choose
     * Upconvert, MediaConvert includes the captions data in two ways: it passes the STL data through using the Teletext
     * compatibility bytes fields of the Teletext wrapper, and it also translates the STL data into Teletext.
     *
     * @var CaptionSourceUpconvertSTLToTeletext::*|null
     */
    private $upconvertStlToTeletext;

    /**
     * @param array{
     *   ByteRateLimit?: null|CaptionSourceByteRateLimit::*,
     *   Convert608To708?: null|FileSourceConvert608To708::*,
     *   ConvertPaintToPop?: null|CaptionSourceConvertPaintOnToPopOn::*,
     *   Framerate?: null|CaptionSourceFramerate|array,
     *   SourceFile?: null|string,
     *   TimeDelta?: null|int,
     *   TimeDeltaUnits?: null|FileSourceTimeDeltaUnits::*,
     *   UpconvertSTLToTeletext?: null|CaptionSourceUpconvertSTLToTeletext::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->byteRateLimit = $input['ByteRateLimit'] ?? null;
        $this->convert608To708 = $input['Convert608To708'] ?? null;
        $this->convertPaintToPop = $input['ConvertPaintToPop'] ?? null;
        $this->framerate = isset($input['Framerate']) ? CaptionSourceFramerate::create($input['Framerate']) : null;
        $this->sourceFile = $input['SourceFile'] ?? null;
        $this->timeDelta = $input['TimeDelta'] ?? null;
        $this->timeDeltaUnits = $input['TimeDeltaUnits'] ?? null;
        $this->upconvertStlToTeletext = $input['UpconvertSTLToTeletext'] ?? null;
    }

    /**
     * @param array{
     *   ByteRateLimit?: null|CaptionSourceByteRateLimit::*,
     *   Convert608To708?: null|FileSourceConvert608To708::*,
     *   ConvertPaintToPop?: null|CaptionSourceConvertPaintOnToPopOn::*,
     *   Framerate?: null|CaptionSourceFramerate|array,
     *   SourceFile?: null|string,
     *   TimeDelta?: null|int,
     *   TimeDeltaUnits?: null|FileSourceTimeDeltaUnits::*,
     *   UpconvertSTLToTeletext?: null|CaptionSourceUpconvertSTLToTeletext::*,
     * }|FileSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CaptionSourceByteRateLimit::*|null
     */
    public function getByteRateLimit(): ?string
    {
        return $this->byteRateLimit;
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
     * @return CaptionSourceUpconvertSTLToTeletext::*|null
     */
    public function getUpconvertStlToTeletext(): ?string
    {
        return $this->upconvertStlToTeletext;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->byteRateLimit) {
            if (!CaptionSourceByteRateLimit::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "byteRateLimit" for "%s". The value "%s" is not a valid "CaptionSourceByteRateLimit".', __CLASS__, $v));
            }
            $payload['byteRateLimit'] = $v;
        }
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
        if (null !== $v = $this->upconvertStlToTeletext) {
            if (!CaptionSourceUpconvertSTLToTeletext::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "upconvertSTLToTeletext" for "%s". The value "%s" is not a valid "CaptionSourceUpconvertSTLToTeletext".', __CLASS__, $v));
            }
            $payload['upconvertSTLToTeletext'] = $v;
        }

        return $payload;
    }
}
