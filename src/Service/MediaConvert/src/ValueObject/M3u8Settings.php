<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\M3u8AudioDuration;
use AsyncAws\MediaConvert\Enum\M3u8DataPtsControl;
use AsyncAws\MediaConvert\Enum\M3u8NielsenId3;
use AsyncAws\MediaConvert\Enum\M3u8PcrControl;
use AsyncAws\MediaConvert\Enum\M3u8Scte35Source;
use AsyncAws\MediaConvert\Enum\TimedMetadata;
use AsyncAws\MediaConvert\Enum\TsPtsOffset;

/**
 * These settings relate to the MPEG-2 transport stream (MPEG2-TS) container for the MPEG2-TS segments in your HLS
 * outputs.
 */
final class M3u8Settings
{
    /**
     * Specify this setting only when your output will be consumed by a downstream repackaging workflow that is sensitive to
     * very small duration differences between video and audio. For this situation, choose Match video duration. In all
     * other cases, keep the default value, Default codec duration. When you choose Match video duration, MediaConvert pads
     * the output audio streams with silence or trims them to ensure that the total duration of each audio stream is at
     * least as long as the total duration of the video stream. After padding or trimming, the audio stream duration is no
     * more than one frame longer than the video stream. MediaConvert applies audio padding or trimming only to the end of
     * the last segment of the output. For unsegmented outputs, MediaConvert adds padding only to the end of the file. When
     * you keep the default value, any minor discrepancies between audio and video duration will depend on your output audio
     * codec.
     *
     * @var M3u8AudioDuration::*|string|null
     */
    private $audioDuration;

    /**
     * The number of audio frames to insert for each PES packet.
     *
     * @var int|null
     */
    private $audioFramesPerPes;

    /**
     * Packet Identifier (PID) of the elementary audio stream(s) in the transport stream. Multiple values are accepted, and
     * can be entered in ranges and/or by comma separation.
     *
     * @var int[]|null
     */
    private $audioPids;

    /**
     * Manually specify the difference in PTS offset that will be applied to the audio track, in seconds or milliseconds,
     * when you set PTS offset to Seconds or Milliseconds. Enter an integer from -10000 to 10000. Leave blank to keep the
     * default value 0.
     *
     * @var int|null
     */
    private $audioPtsOffsetDelta;

    /**
     * If you select ALIGN_TO_VIDEO, MediaConvert writes captions and data packets with Presentation Timestamp (PTS) values
     * greater than or equal to the first video packet PTS (MediaConvert drops captions and data packets with lesser PTS
     * values). Keep the default value AUTO to allow all PTS values.
     *
     * @var M3u8DataPtsControl::*|string|null
     */
    private $dataPtsControl;

    /**
     * Specify the maximum time, in milliseconds, between Program Clock References (PCRs) inserted into the transport
     * stream.
     *
     * @var int|null
     */
    private $maxPcrInterval;

    /**
     * If INSERT, Nielsen inaudible tones for media tracking will be detected in the input audio and an equivalent ID3 tag
     * will be inserted in the output.
     *
     * @var M3u8NielsenId3::*|string|null
     */
    private $nielsenId3;

    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     *
     * @var int|null
     */
    private $patInterval;

    /**
     * When set to PCR_EVERY_PES_PACKET a Program Clock Reference value is inserted for every Packetized Elementary Stream
     * (PES) header. This parameter is effective only when the PCR PID is the same as the video or audio elementary stream.
     *
     * @var M3u8PcrControl::*|string|null
     */
    private $pcrControl;

    /**
     * Packet Identifier (PID) of the Program Clock Reference (PCR) in the transport stream. When no value is given, the
     * encoder will assign the same value as the Video PID.
     *
     * @var int|null
     */
    private $pcrPid;

    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     *
     * @var int|null
     */
    private $pmtInterval;

    /**
     * Packet Identifier (PID) for the Program Map Table (PMT) in the transport stream.
     *
     * @var int|null
     */
    private $pmtPid;

    /**
     * Packet Identifier (PID) of the private metadata stream in the transport stream.
     *
     * @var int|null
     */
    private $privateMetadataPid;

    /**
     * The value of the program number field in the Program Map Table.
     *
     * @var int|null
     */
    private $programNumber;

    /**
     * Manually specify the initial PTS offset, in seconds, when you set PTS offset to Seconds. Enter an integer from 0 to
     * 3600. Leave blank to keep the default value 2.
     *
     * @var int|null
     */
    private $ptsOffset;

    /**
     * Specify the initial presentation timestamp (PTS) offset for your transport stream output. To let MediaConvert
     * automatically determine the initial PTS offset: Keep the default value, Auto. We recommend that you choose Auto for
     * the widest player compatibility. The initial PTS will be at least two seconds and vary depending on your output's
     * bitrate, HRD buffer size and HRD buffer initial fill percentage. To manually specify an initial PTS offset: Choose
     * Seconds or Milliseconds. Then specify the number of seconds or milliseconds with PTS offset.
     *
     * @var TsPtsOffset::*|string|null
     */
    private $ptsOffsetMode;

    /**
     * Packet Identifier (PID) of the SCTE-35 stream in the transport stream.
     *
     * @var int|null
     */
    private $scte35Pid;

    /**
     * For SCTE-35 markers from your input-- Choose Passthrough if you want SCTE-35 markers that appear in your input to
     * also appear in this output. Choose None if you don't want SCTE-35 markers in this output. For SCTE-35 markers from an
     * ESAM XML document-- Choose None if you don't want manifest conditioning. Choose Passthrough and choose Ad markers if
     * you do want manifest conditioning. In both cases, also provide the ESAM XML as a string in the setting Signal
     * processing notification XML.
     *
     * @var M3u8Scte35Source::*|string|null
     */
    private $scte35Source;

    /**
     * Set ID3 metadata to Passthrough to include ID3 metadata in this output. This includes ID3 metadata from the following
     * features: ID3 timestamp period, and Custom ID3 metadata inserter. To exclude this ID3 metadata in this output: set
     * ID3 metadata to None or leave blank.
     *
     * @var TimedMetadata::*|string|null
     */
    private $timedMetadata;

    /**
     * Packet Identifier (PID) of the ID3 metadata stream in the transport stream.
     *
     * @var int|null
     */
    private $timedMetadataPid;

    /**
     * The value of the transport stream ID field in the Program Map Table.
     *
     * @var int|null
     */
    private $transportStreamId;

    /**
     * Packet Identifier (PID) of the elementary video stream in the transport stream.
     *
     * @var int|null
     */
    private $videoPid;

    /**
     * @param array{
     *   AudioDuration?: null|M3u8AudioDuration::*|string,
     *   AudioFramesPerPes?: null|int,
     *   AudioPids?: null|int[],
     *   AudioPtsOffsetDelta?: null|int,
     *   DataPTSControl?: null|M3u8DataPtsControl::*|string,
     *   MaxPcrInterval?: null|int,
     *   NielsenId3?: null|M3u8NielsenId3::*|string,
     *   PatInterval?: null|int,
     *   PcrControl?: null|M3u8PcrControl::*|string,
     *   PcrPid?: null|int,
     *   PmtInterval?: null|int,
     *   PmtPid?: null|int,
     *   PrivateMetadataPid?: null|int,
     *   ProgramNumber?: null|int,
     *   PtsOffset?: null|int,
     *   PtsOffsetMode?: null|TsPtsOffset::*|string,
     *   Scte35Pid?: null|int,
     *   Scte35Source?: null|M3u8Scte35Source::*|string,
     *   TimedMetadata?: null|TimedMetadata::*|string,
     *   TimedMetadataPid?: null|int,
     *   TransportStreamId?: null|int,
     *   VideoPid?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDuration = $input['AudioDuration'] ?? null;
        $this->audioFramesPerPes = $input['AudioFramesPerPes'] ?? null;
        $this->audioPids = $input['AudioPids'] ?? null;
        $this->audioPtsOffsetDelta = $input['AudioPtsOffsetDelta'] ?? null;
        $this->dataPtsControl = $input['DataPTSControl'] ?? null;
        $this->maxPcrInterval = $input['MaxPcrInterval'] ?? null;
        $this->nielsenId3 = $input['NielsenId3'] ?? null;
        $this->patInterval = $input['PatInterval'] ?? null;
        $this->pcrControl = $input['PcrControl'] ?? null;
        $this->pcrPid = $input['PcrPid'] ?? null;
        $this->pmtInterval = $input['PmtInterval'] ?? null;
        $this->pmtPid = $input['PmtPid'] ?? null;
        $this->privateMetadataPid = $input['PrivateMetadataPid'] ?? null;
        $this->programNumber = $input['ProgramNumber'] ?? null;
        $this->ptsOffset = $input['PtsOffset'] ?? null;
        $this->ptsOffsetMode = $input['PtsOffsetMode'] ?? null;
        $this->scte35Pid = $input['Scte35Pid'] ?? null;
        $this->scte35Source = $input['Scte35Source'] ?? null;
        $this->timedMetadata = $input['TimedMetadata'] ?? null;
        $this->timedMetadataPid = $input['TimedMetadataPid'] ?? null;
        $this->transportStreamId = $input['TransportStreamId'] ?? null;
        $this->videoPid = $input['VideoPid'] ?? null;
    }

    /**
     * @param array{
     *   AudioDuration?: null|M3u8AudioDuration::*|string,
     *   AudioFramesPerPes?: null|int,
     *   AudioPids?: null|int[],
     *   AudioPtsOffsetDelta?: null|int,
     *   DataPTSControl?: null|M3u8DataPtsControl::*|string,
     *   MaxPcrInterval?: null|int,
     *   NielsenId3?: null|M3u8NielsenId3::*|string,
     *   PatInterval?: null|int,
     *   PcrControl?: null|M3u8PcrControl::*|string,
     *   PcrPid?: null|int,
     *   PmtInterval?: null|int,
     *   PmtPid?: null|int,
     *   PrivateMetadataPid?: null|int,
     *   ProgramNumber?: null|int,
     *   PtsOffset?: null|int,
     *   PtsOffsetMode?: null|TsPtsOffset::*|string,
     *   Scte35Pid?: null|int,
     *   Scte35Source?: null|M3u8Scte35Source::*|string,
     *   TimedMetadata?: null|TimedMetadata::*|string,
     *   TimedMetadataPid?: null|int,
     *   TransportStreamId?: null|int,
     *   VideoPid?: null|int,
     * }|M3u8Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return M3u8AudioDuration::*|string|null
     */
    public function getAudioDuration(): ?string
    {
        return $this->audioDuration;
    }

    public function getAudioFramesPerPes(): ?int
    {
        return $this->audioFramesPerPes;
    }

    /**
     * @return int[]
     */
    public function getAudioPids(): array
    {
        return $this->audioPids ?? [];
    }

    public function getAudioPtsOffsetDelta(): ?int
    {
        return $this->audioPtsOffsetDelta;
    }

    /**
     * @return M3u8DataPtsControl::*|string|null
     */
    public function getDataPtsControl(): ?string
    {
        return $this->dataPtsControl;
    }

    public function getMaxPcrInterval(): ?int
    {
        return $this->maxPcrInterval;
    }

    /**
     * @return M3u8NielsenId3::*|string|null
     */
    public function getNielsenId3(): ?string
    {
        return $this->nielsenId3;
    }

    public function getPatInterval(): ?int
    {
        return $this->patInterval;
    }

    /**
     * @return M3u8PcrControl::*|string|null
     */
    public function getPcrControl(): ?string
    {
        return $this->pcrControl;
    }

    public function getPcrPid(): ?int
    {
        return $this->pcrPid;
    }

    public function getPmtInterval(): ?int
    {
        return $this->pmtInterval;
    }

    public function getPmtPid(): ?int
    {
        return $this->pmtPid;
    }

    public function getPrivateMetadataPid(): ?int
    {
        return $this->privateMetadataPid;
    }

    public function getProgramNumber(): ?int
    {
        return $this->programNumber;
    }

    public function getPtsOffset(): ?int
    {
        return $this->ptsOffset;
    }

    /**
     * @return TsPtsOffset::*|string|null
     */
    public function getPtsOffsetMode(): ?string
    {
        return $this->ptsOffsetMode;
    }

    public function getScte35Pid(): ?int
    {
        return $this->scte35Pid;
    }

    /**
     * @return M3u8Scte35Source::*|string|null
     */
    public function getScte35Source(): ?string
    {
        return $this->scte35Source;
    }

    /**
     * @return TimedMetadata::*|string|null
     */
    public function getTimedMetadata(): ?string
    {
        return $this->timedMetadata;
    }

    public function getTimedMetadataPid(): ?int
    {
        return $this->timedMetadataPid;
    }

    public function getTransportStreamId(): ?int
    {
        return $this->transportStreamId;
    }

    public function getVideoPid(): ?int
    {
        return $this->videoPid;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDuration) {
            if (!M3u8AudioDuration::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDuration" for "%s". The value "%s" is not a valid "M3u8AudioDuration".', __CLASS__, $v));
            }
            $payload['audioDuration'] = $v;
        }
        if (null !== $v = $this->audioFramesPerPes) {
            $payload['audioFramesPerPes'] = $v;
        }
        if (null !== $v = $this->audioPids) {
            $index = -1;
            $payload['audioPids'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['audioPids'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->audioPtsOffsetDelta) {
            $payload['audioPtsOffsetDelta'] = $v;
        }
        if (null !== $v = $this->dataPtsControl) {
            if (!M3u8DataPtsControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dataPTSControl" for "%s". The value "%s" is not a valid "M3u8DataPtsControl".', __CLASS__, $v));
            }
            $payload['dataPTSControl'] = $v;
        }
        if (null !== $v = $this->maxPcrInterval) {
            $payload['maxPcrInterval'] = $v;
        }
        if (null !== $v = $this->nielsenId3) {
            if (!M3u8NielsenId3::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "nielsenId3" for "%s". The value "%s" is not a valid "M3u8NielsenId3".', __CLASS__, $v));
            }
            $payload['nielsenId3'] = $v;
        }
        if (null !== $v = $this->patInterval) {
            $payload['patInterval'] = $v;
        }
        if (null !== $v = $this->pcrControl) {
            if (!M3u8PcrControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "pcrControl" for "%s". The value "%s" is not a valid "M3u8PcrControl".', __CLASS__, $v));
            }
            $payload['pcrControl'] = $v;
        }
        if (null !== $v = $this->pcrPid) {
            $payload['pcrPid'] = $v;
        }
        if (null !== $v = $this->pmtInterval) {
            $payload['pmtInterval'] = $v;
        }
        if (null !== $v = $this->pmtPid) {
            $payload['pmtPid'] = $v;
        }
        if (null !== $v = $this->privateMetadataPid) {
            $payload['privateMetadataPid'] = $v;
        }
        if (null !== $v = $this->programNumber) {
            $payload['programNumber'] = $v;
        }
        if (null !== $v = $this->ptsOffset) {
            $payload['ptsOffset'] = $v;
        }
        if (null !== $v = $this->ptsOffsetMode) {
            if (!TsPtsOffset::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ptsOffsetMode" for "%s". The value "%s" is not a valid "TsPtsOffset".', __CLASS__, $v));
            }
            $payload['ptsOffsetMode'] = $v;
        }
        if (null !== $v = $this->scte35Pid) {
            $payload['scte35Pid'] = $v;
        }
        if (null !== $v = $this->scte35Source) {
            if (!M3u8Scte35Source::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scte35Source" for "%s". The value "%s" is not a valid "M3u8Scte35Source".', __CLASS__, $v));
            }
            $payload['scte35Source'] = $v;
        }
        if (null !== $v = $this->timedMetadata) {
            if (!TimedMetadata::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timedMetadata" for "%s". The value "%s" is not a valid "TimedMetadata".', __CLASS__, $v));
            }
            $payload['timedMetadata'] = $v;
        }
        if (null !== $v = $this->timedMetadataPid) {
            $payload['timedMetadataPid'] = $v;
        }
        if (null !== $v = $this->transportStreamId) {
            $payload['transportStreamId'] = $v;
        }
        if (null !== $v = $this->videoPid) {
            $payload['videoPid'] = $v;
        }

        return $payload;
    }
}
