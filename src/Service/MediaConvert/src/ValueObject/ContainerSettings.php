<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\ContainerType;

/**
 * Container specific settings.
 */
final class ContainerSettings
{
    /**
     * These settings relate to the fragmented MP4 container for the segments in your CMAF outputs.
     *
     * @var CmfcSettings|null
     */
    private $cmfcSettings;

    /**
     * Container for this output. Some containers require a container settings object. If not specified, the default object
     * will be created.
     *
     * @var ContainerType::*|string|null
     */
    private $container;

    /**
     * Settings for F4v container.
     *
     * @var F4vSettings|null
     */
    private $f4vSettings;

    /**
     * MPEG-2 TS container settings. These apply to outputs in a File output group when the output's container is MPEG-2
     * Transport Stream (M2TS). In these assets, data is organized by the program map table (PMT). Each transport stream
     * program contains subsets of data, including audio, video, and metadata. Each of these subsets of data has a numerical
     * label called a packet identifier (PID). Each transport stream program corresponds to one MediaConvert output. The PMT
     * lists the types of data in a program along with their PID. Downstream systems and players use the program map table
     * to look up the PID for each type of data it accesses and then uses the PIDs to locate specific data within the asset.
     *
     * @var M2tsSettings|null
     */
    private $m2tsSettings;

    /**
     * These settings relate to the MPEG-2 transport stream (MPEG2-TS) container for the MPEG2-TS segments in your HLS
     * outputs.
     *
     * @var M3u8Settings|null
     */
    private $m3u8Settings;

    /**
     * These settings relate to your QuickTime MOV output container.
     *
     * @var MovSettings|null
     */
    private $movSettings;

    /**
     * These settings relate to your MP4 output container. You can create audio only outputs with this container. For more
     * information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/supported-codecs-containers-audio-only.html#output-codecs-and-containers-supported-for-audio-only.
     *
     * @var Mp4Settings|null
     */
    private $mp4Settings;

    /**
     * These settings relate to the fragmented MP4 container for the segments in your DASH outputs.
     *
     * @var MpdSettings|null
     */
    private $mpdSettings;

    /**
     * These settings relate to your MXF output container.
     *
     * @var MxfSettings|null
     */
    private $mxfSettings;

    /**
     * @param array{
     *   CmfcSettings?: null|CmfcSettings|array,
     *   Container?: null|ContainerType::*|string,
     *   F4vSettings?: null|F4vSettings|array,
     *   M2tsSettings?: null|M2tsSettings|array,
     *   M3u8Settings?: null|M3u8Settings|array,
     *   MovSettings?: null|MovSettings|array,
     *   Mp4Settings?: null|Mp4Settings|array,
     *   MpdSettings?: null|MpdSettings|array,
     *   MxfSettings?: null|MxfSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cmfcSettings = isset($input['CmfcSettings']) ? CmfcSettings::create($input['CmfcSettings']) : null;
        $this->container = $input['Container'] ?? null;
        $this->f4vSettings = isset($input['F4vSettings']) ? F4vSettings::create($input['F4vSettings']) : null;
        $this->m2tsSettings = isset($input['M2tsSettings']) ? M2tsSettings::create($input['M2tsSettings']) : null;
        $this->m3u8Settings = isset($input['M3u8Settings']) ? M3u8Settings::create($input['M3u8Settings']) : null;
        $this->movSettings = isset($input['MovSettings']) ? MovSettings::create($input['MovSettings']) : null;
        $this->mp4Settings = isset($input['Mp4Settings']) ? Mp4Settings::create($input['Mp4Settings']) : null;
        $this->mpdSettings = isset($input['MpdSettings']) ? MpdSettings::create($input['MpdSettings']) : null;
        $this->mxfSettings = isset($input['MxfSettings']) ? MxfSettings::create($input['MxfSettings']) : null;
    }

    /**
     * @param array{
     *   CmfcSettings?: null|CmfcSettings|array,
     *   Container?: null|ContainerType::*|string,
     *   F4vSettings?: null|F4vSettings|array,
     *   M2tsSettings?: null|M2tsSettings|array,
     *   M3u8Settings?: null|M3u8Settings|array,
     *   MovSettings?: null|MovSettings|array,
     *   Mp4Settings?: null|Mp4Settings|array,
     *   MpdSettings?: null|MpdSettings|array,
     *   MxfSettings?: null|MxfSettings|array,
     * }|ContainerSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCmfcSettings(): ?CmfcSettings
    {
        return $this->cmfcSettings;
    }

    /**
     * @return ContainerType::*|string|null
     */
    public function getContainer(): ?string
    {
        return $this->container;
    }

    public function getF4vSettings(): ?F4vSettings
    {
        return $this->f4vSettings;
    }

    public function getM2tsSettings(): ?M2tsSettings
    {
        return $this->m2tsSettings;
    }

    public function getM3u8Settings(): ?M3u8Settings
    {
        return $this->m3u8Settings;
    }

    public function getMovSettings(): ?MovSettings
    {
        return $this->movSettings;
    }

    public function getMp4Settings(): ?Mp4Settings
    {
        return $this->mp4Settings;
    }

    public function getMpdSettings(): ?MpdSettings
    {
        return $this->mpdSettings;
    }

    public function getMxfSettings(): ?MxfSettings
    {
        return $this->mxfSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->cmfcSettings) {
            $payload['cmfcSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->container) {
            if (!ContainerType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "container" for "%s". The value "%s" is not a valid "ContainerType".', __CLASS__, $v));
            }
            $payload['container'] = $v;
        }
        if (null !== $v = $this->f4vSettings) {
            $payload['f4vSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->m2tsSettings) {
            $payload['m2tsSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->m3u8Settings) {
            $payload['m3u8Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->movSettings) {
            $payload['movSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->mp4Settings) {
            $payload['mp4Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->mpdSettings) {
            $payload['mpdSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->mxfSettings) {
            $payload['mxfSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
