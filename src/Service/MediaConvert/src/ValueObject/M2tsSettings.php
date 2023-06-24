<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\M2tsAudioBufferModel;
use AsyncAws\MediaConvert\Enum\M2tsAudioDuration;
use AsyncAws\MediaConvert\Enum\M2tsBufferModel;
use AsyncAws\MediaConvert\Enum\M2tsDataPtsControl;
use AsyncAws\MediaConvert\Enum\M2tsEbpAudioInterval;
use AsyncAws\MediaConvert\Enum\M2tsEbpPlacement;
use AsyncAws\MediaConvert\Enum\M2tsEsRateInPes;
use AsyncAws\MediaConvert\Enum\M2tsForceTsVideoEbpOrder;
use AsyncAws\MediaConvert\Enum\M2tsKlvMetadata;
use AsyncAws\MediaConvert\Enum\M2tsNielsenId3;
use AsyncAws\MediaConvert\Enum\M2tsPcrControl;
use AsyncAws\MediaConvert\Enum\M2tsRateMode;
use AsyncAws\MediaConvert\Enum\M2tsScte35Source;
use AsyncAws\MediaConvert\Enum\M2tsSegmentationMarkers;
use AsyncAws\MediaConvert\Enum\M2tsSegmentationStyle;

/**
 * MPEG-2 TS container settings. These apply to outputs in a File output group when the output's container
 * (ContainerType) is MPEG-2 Transport Stream (M2TS). In these assets, data is organized by the program map table (PMT).
 * Each transport stream program contains subsets of data, including audio, video, and metadata. Each of these subsets
 * of data has a numerical label called a packet identifier (PID). Each transport stream program corresponds to one
 * MediaConvert output. The PMT lists the types of data in a program along with their PID. Downstream systems and
 * players use the program map table to look up the PID for each type of data it accesses and then uses the PIDs to
 * locate specific data within the asset.
 */
final class M2tsSettings
{
    /**
     * Selects between the DVB and ATSC buffer models for Dolby Digital audio.
     */
    private $audioBufferModel;

    /**
     * Specify this setting only when your output will be consumed by a downstream repackaging workflow that is sensitive to
     * very small duration differences between video and audio. For this situation, choose Match video duration
     * (MATCH_VIDEO_DURATION). In all other cases, keep the default value, Default codec duration (DEFAULT_CODEC_DURATION).
     * When you choose Match video duration, MediaConvert pads the output audio streams with silence or trims them to ensure
     * that the total duration of each audio stream is at least as long as the total duration of the video stream. After
     * padding or trimming, the audio stream duration is no more than one frame longer than the video stream. MediaConvert
     * applies audio padding or trimming only to the end of the last segment of the output. For unsegmented outputs,
     * MediaConvert adds padding only to the end of the file. When you keep the default value, any minor discrepancies
     * between audio and video duration will depend on your output audio codec.
     */
    private $audioDuration;

    /**
     * The number of audio frames to insert for each PES packet.
     */
    private $audioFramesPerPes;

    /**
     * Specify the packet identifiers (PIDs) for any elementary audio streams you include in this output. Specify multiple
     * PIDs as a JSON array. Default is the range 482-492.
     */
    private $audioPids;

    /**
     * Specify the output bitrate of the transport stream in bits per second. Setting to 0 lets the muxer automatically
     * determine the appropriate bitrate. Other common values are 3750000, 7500000, and 15000000.
     */
    private $bitrate;

    /**
     * Controls what buffer model to use for accurate interleaving. If set to MULTIPLEX, use multiplex buffer model. If set
     * to NONE, this can lead to lower latency, but low-memory devices may not be able to play back the stream without
     * interruptions.
     */
    private $bufferModel;

    /**
     * If you select ALIGN_TO_VIDEO, MediaConvert writes captions and data packets with Presentation Timestamp (PTS) values
     * greater than or equal to the first video packet PTS (MediaConvert drops captions and data packets with lesser PTS
     * values). Keep the default value (AUTO) to allow all PTS values.
     */
    private $dataPtsControl;

    /**
     * Use these settings to insert a DVB Network Information Table (NIT) in the transport stream of this output. When you
     * work directly in your JSON job specification, include this object only when your job has a transport stream output
     * and the container settings contain the object M2tsSettings.
     */
    private $dvbNitSettings;

    /**
     * Use these settings to insert a DVB Service Description Table (SDT) in the transport stream of this output. When you
     * work directly in your JSON job specification, include this object only when your job has a transport stream output
     * and the container settings contain the object M2tsSettings.
     */
    private $dvbSdtSettings;

    /**
     * Specify the packet identifiers (PIDs) for DVB subtitle data included in this output. Specify multiple PIDs as a JSON
     * array. Default is the range 460-479.
     */
    private $dvbSubPids;

    /**
     * Use these settings to insert a DVB Time and Date Table (TDT) in the transport stream of this output. When you work
     * directly in your JSON job specification, include this object only when your job has a transport stream output and the
     * container settings contain the object M2tsSettings.
     */
    private $dvbTdtSettings;

    /**
     * Specify the packet identifier (PID) for DVB teletext data you include in this output. Default is 499.
     */
    private $dvbTeletextPid;

    /**
     * When set to VIDEO_AND_FIXED_INTERVALS, audio EBP markers will be added to partitions 3 and 4. The interval between
     * these additional markers will be fixed, and will be slightly shorter than the video EBP marker interval. When set to
     * VIDEO_INTERVAL, these additional markers will not be inserted. Only applicable when EBP segmentation markers are is
     * selected (segmentationMarkers is EBP or EBP_LEGACY).
     */
    private $ebpAudioInterval;

    /**
     * Selects which PIDs to place EBP markers on. They can either be placed only on the video PID, or on both the video PID
     * and all audio PIDs. Only applicable when EBP segmentation markers are is selected (segmentationMarkers is EBP or
     * EBP_LEGACY).
     */
    private $ebpPlacement;

    /**
     * Controls whether to include the ES Rate field in the PES header.
     */
    private $esRateInPes;

    /**
     * Keep the default value (DEFAULT) unless you know that your audio EBP markers are incorrectly appearing before your
     * video EBP markers. To correct this problem, set this value to Force (FORCE).
     */
    private $forceTsVideoEbpOrder;

    /**
     * The length, in seconds, of each fragment. Only used with EBP markers.
     */
    private $fragmentTime;

    /**
     * To include key-length-value metadata in this output: Set KLV metadata insertion to Passthrough. MediaConvert reads
     * KLV metadata present in your input and passes it through to the output transport stream. To exclude this KLV
     * metadata: Set KLV metadata insertion to None or leave blank.
     */
    private $klvMetadata;

    /**
     * Specify the maximum time, in milliseconds, between Program Clock References (PCRs) inserted into the transport
     * stream.
     */
    private $maxPcrInterval;

    /**
     * When set, enforces that Encoder Boundary Points do not come within the specified time interval of each other by
     * looking ahead at input video. If another EBP is going to come in within the specified time interval, the current EBP
     * is not emitted, and the segment is "stretched" to the next marker. The lookahead value does not add latency to the
     * system. The Live Event must be configured elsewhere to create sufficient latency to make the lookahead accurate.
     */
    private $minEbpInterval;

    /**
     * If INSERT, Nielsen inaudible tones for media tracking will be detected in the input audio and an equivalent ID3 tag
     * will be inserted in the output.
     */
    private $nielsenId3;

    /**
     * Value in bits per second of extra null packets to insert into the transport stream. This can be used if a downstream
     * encryption system requires periodic null packets.
     */
    private $nullPacketBitrate;

    /**
     * The number of milliseconds between instances of this table in the output transport stream.
     */
    private $patInterval;

    /**
     * When set to PCR_EVERY_PES_PACKET, a Program Clock Reference value is inserted for every Packetized Elementary Stream
     * (PES) header. This is effective only when the PCR PID is the same as the video or audio elementary stream.
     */
    private $pcrControl;

    /**
     * Specify the packet identifier (PID) for the program clock reference (PCR) in this output. If you do not specify a
     * value, the service will use the value for Video PID (VideoPid).
     */
    private $pcrPid;

    /**
     * Specify the number of milliseconds between instances of the program map table (PMT) in the output transport stream.
     */
    private $pmtInterval;

    /**
     * Specify the packet identifier (PID) for the program map table (PMT) itself. Default is 480.
     */
    private $pmtPid;

    /**
     * Specify the packet identifier (PID) of the private metadata stream. Default is 503.
     */
    private $privateMetadataPid;

    /**
     * Use Program number (programNumber) to specify the program number used in the program map table (PMT) for this output.
     * Default is 1. Program numbers and program map tables are parts of MPEG-2 transport stream containers, used for
     * organizing data.
     */
    private $programNumber;

    /**
     * When set to CBR, inserts null packets into transport stream to fill specified bitrate. When set to VBR, the bitrate
     * setting acts as the maximum bitrate, but the output will not be padded up to that bitrate.
     */
    private $rateMode;

    /**
     * Include this in your job settings to put SCTE-35 markers in your HLS and transport stream outputs at the insertion
     * points that you specify in an ESAM XML document. Provide the document in the setting SCC XML (sccXml).
     */
    private $scte35Esam;

    /**
     * Specify the packet identifier (PID) of the SCTE-35 stream in the transport stream.
     */
    private $scte35Pid;

    /**
     * For SCTE-35 markers from your input-- Choose Passthrough (PASSTHROUGH) if you want SCTE-35 markers that appear in
     * your input to also appear in this output. Choose None (NONE) if you don't want SCTE-35 markers in this output. For
     * SCTE-35 markers from an ESAM XML document-- Choose None (NONE). Also provide the ESAM XML as a string in the setting
     * Signal processing notification XML (sccXml). Also enable ESAM SCTE-35 (include the property scte35Esam).
     */
    private $scte35Source;

    /**
     * Inserts segmentation markers at each segmentation_time period. rai_segstart sets the Random Access Indicator bit in
     * the adaptation field. rai_adapt sets the RAI bit and adds the current timecode in the private data bytes.
     * psi_segstart inserts PAT and PMT tables at the start of segments. ebp adds Encoder Boundary Point information to the
     * adaptation field as per OpenCable specification OC-SP-EBP-I01-130118. ebp_legacy adds Encoder Boundary Point
     * information to the adaptation field using a legacy proprietary format.
     */
    private $segmentationMarkers;

    /**
     * The segmentation style parameter controls how segmentation markers are inserted into the transport stream. With
     * avails, it is possible that segments may be truncated, which can influence where future segmentation markers are
     * inserted. When a segmentation style of "reset_cadence" is selected and a segment is truncated due to an avail, we
     * will reset the segmentation cadence. This means the subsequent segment will have a duration of of $segmentation_time
     * seconds. When a segmentation style of "maintain_cadence" is selected and a segment is truncated due to an avail, we
     * will not reset the segmentation cadence. This means the subsequent segment will likely be truncated as well. However,
     * all segments after that will have a duration of $segmentation_time seconds. Note that EBP lookahead is a slight
     * exception to this rule.
     */
    private $segmentationStyle;

    /**
     * Specify the length, in seconds, of each segment. Required unless markers is set to _none_.
     */
    private $segmentationTime;

    /**
     * Packet Identifier (PID) of the ID3 metadata stream in the transport stream.
     */
    private $timedMetadataPid;

    /**
     * Specify the ID for the transport stream itself in the program map table for this output. Transport stream IDs and
     * program map tables are parts of MPEG-2 transport stream containers, used for organizing data.
     */
    private $transportStreamId;

    /**
     * Specify the packet identifier (PID) of the elementary video stream in the transport stream.
     */
    private $videoPid;

    /**
     * @param array{
     *   AudioBufferModel?: null|M2tsAudioBufferModel::*,
     *   AudioDuration?: null|M2tsAudioDuration::*,
     *   AudioFramesPerPes?: null|int,
     *   AudioPids?: null|int[],
     *   Bitrate?: null|int,
     *   BufferModel?: null|M2tsBufferModel::*,
     *   DataPTSControl?: null|M2tsDataPtsControl::*,
     *   DvbNitSettings?: null|DvbNitSettings|array,
     *   DvbSdtSettings?: null|DvbSdtSettings|array,
     *   DvbSubPids?: null|int[],
     *   DvbTdtSettings?: null|DvbTdtSettings|array,
     *   DvbTeletextPid?: null|int,
     *   EbpAudioInterval?: null|M2tsEbpAudioInterval::*,
     *   EbpPlacement?: null|M2tsEbpPlacement::*,
     *   EsRateInPes?: null|M2tsEsRateInPes::*,
     *   ForceTsVideoEbpOrder?: null|M2tsForceTsVideoEbpOrder::*,
     *   FragmentTime?: null|float,
     *   KlvMetadata?: null|M2tsKlvMetadata::*,
     *   MaxPcrInterval?: null|int,
     *   MinEbpInterval?: null|int,
     *   NielsenId3?: null|M2tsNielsenId3::*,
     *   NullPacketBitrate?: null|float,
     *   PatInterval?: null|int,
     *   PcrControl?: null|M2tsPcrControl::*,
     *   PcrPid?: null|int,
     *   PmtInterval?: null|int,
     *   PmtPid?: null|int,
     *   PrivateMetadataPid?: null|int,
     *   ProgramNumber?: null|int,
     *   RateMode?: null|M2tsRateMode::*,
     *   Scte35Esam?: null|M2tsScte35Esam|array,
     *   Scte35Pid?: null|int,
     *   Scte35Source?: null|M2tsScte35Source::*,
     *   SegmentationMarkers?: null|M2tsSegmentationMarkers::*,
     *   SegmentationStyle?: null|M2tsSegmentationStyle::*,
     *   SegmentationTime?: null|float,
     *   TimedMetadataPid?: null|int,
     *   TransportStreamId?: null|int,
     *   VideoPid?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioBufferModel = $input['AudioBufferModel'] ?? null;
        $this->audioDuration = $input['AudioDuration'] ?? null;
        $this->audioFramesPerPes = $input['AudioFramesPerPes'] ?? null;
        $this->audioPids = $input['AudioPids'] ?? null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->bufferModel = $input['BufferModel'] ?? null;
        $this->dataPtsControl = $input['DataPTSControl'] ?? null;
        $this->dvbNitSettings = isset($input['DvbNitSettings']) ? DvbNitSettings::create($input['DvbNitSettings']) : null;
        $this->dvbSdtSettings = isset($input['DvbSdtSettings']) ? DvbSdtSettings::create($input['DvbSdtSettings']) : null;
        $this->dvbSubPids = $input['DvbSubPids'] ?? null;
        $this->dvbTdtSettings = isset($input['DvbTdtSettings']) ? DvbTdtSettings::create($input['DvbTdtSettings']) : null;
        $this->dvbTeletextPid = $input['DvbTeletextPid'] ?? null;
        $this->ebpAudioInterval = $input['EbpAudioInterval'] ?? null;
        $this->ebpPlacement = $input['EbpPlacement'] ?? null;
        $this->esRateInPes = $input['EsRateInPes'] ?? null;
        $this->forceTsVideoEbpOrder = $input['ForceTsVideoEbpOrder'] ?? null;
        $this->fragmentTime = $input['FragmentTime'] ?? null;
        $this->klvMetadata = $input['KlvMetadata'] ?? null;
        $this->maxPcrInterval = $input['MaxPcrInterval'] ?? null;
        $this->minEbpInterval = $input['MinEbpInterval'] ?? null;
        $this->nielsenId3 = $input['NielsenId3'] ?? null;
        $this->nullPacketBitrate = $input['NullPacketBitrate'] ?? null;
        $this->patInterval = $input['PatInterval'] ?? null;
        $this->pcrControl = $input['PcrControl'] ?? null;
        $this->pcrPid = $input['PcrPid'] ?? null;
        $this->pmtInterval = $input['PmtInterval'] ?? null;
        $this->pmtPid = $input['PmtPid'] ?? null;
        $this->privateMetadataPid = $input['PrivateMetadataPid'] ?? null;
        $this->programNumber = $input['ProgramNumber'] ?? null;
        $this->rateMode = $input['RateMode'] ?? null;
        $this->scte35Esam = isset($input['Scte35Esam']) ? M2tsScte35Esam::create($input['Scte35Esam']) : null;
        $this->scte35Pid = $input['Scte35Pid'] ?? null;
        $this->scte35Source = $input['Scte35Source'] ?? null;
        $this->segmentationMarkers = $input['SegmentationMarkers'] ?? null;
        $this->segmentationStyle = $input['SegmentationStyle'] ?? null;
        $this->segmentationTime = $input['SegmentationTime'] ?? null;
        $this->timedMetadataPid = $input['TimedMetadataPid'] ?? null;
        $this->transportStreamId = $input['TransportStreamId'] ?? null;
        $this->videoPid = $input['VideoPid'] ?? null;
    }

    /**
     * @param array{
     *   AudioBufferModel?: null|M2tsAudioBufferModel::*,
     *   AudioDuration?: null|M2tsAudioDuration::*,
     *   AudioFramesPerPes?: null|int,
     *   AudioPids?: null|int[],
     *   Bitrate?: null|int,
     *   BufferModel?: null|M2tsBufferModel::*,
     *   DataPTSControl?: null|M2tsDataPtsControl::*,
     *   DvbNitSettings?: null|DvbNitSettings|array,
     *   DvbSdtSettings?: null|DvbSdtSettings|array,
     *   DvbSubPids?: null|int[],
     *   DvbTdtSettings?: null|DvbTdtSettings|array,
     *   DvbTeletextPid?: null|int,
     *   EbpAudioInterval?: null|M2tsEbpAudioInterval::*,
     *   EbpPlacement?: null|M2tsEbpPlacement::*,
     *   EsRateInPes?: null|M2tsEsRateInPes::*,
     *   ForceTsVideoEbpOrder?: null|M2tsForceTsVideoEbpOrder::*,
     *   FragmentTime?: null|float,
     *   KlvMetadata?: null|M2tsKlvMetadata::*,
     *   MaxPcrInterval?: null|int,
     *   MinEbpInterval?: null|int,
     *   NielsenId3?: null|M2tsNielsenId3::*,
     *   NullPacketBitrate?: null|float,
     *   PatInterval?: null|int,
     *   PcrControl?: null|M2tsPcrControl::*,
     *   PcrPid?: null|int,
     *   PmtInterval?: null|int,
     *   PmtPid?: null|int,
     *   PrivateMetadataPid?: null|int,
     *   ProgramNumber?: null|int,
     *   RateMode?: null|M2tsRateMode::*,
     *   Scte35Esam?: null|M2tsScte35Esam|array,
     *   Scte35Pid?: null|int,
     *   Scte35Source?: null|M2tsScte35Source::*,
     *   SegmentationMarkers?: null|M2tsSegmentationMarkers::*,
     *   SegmentationStyle?: null|M2tsSegmentationStyle::*,
     *   SegmentationTime?: null|float,
     *   TimedMetadataPid?: null|int,
     *   TransportStreamId?: null|int,
     *   VideoPid?: null|int,
     * }|M2tsSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return M2tsAudioBufferModel::*|null
     */
    public function getAudioBufferModel(): ?string
    {
        return $this->audioBufferModel;
    }

    /**
     * @return M2tsAudioDuration::*|null
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

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * @return M2tsBufferModel::*|null
     */
    public function getBufferModel(): ?string
    {
        return $this->bufferModel;
    }

    /**
     * @return M2tsDataPtsControl::*|null
     */
    public function getDataPtsControl(): ?string
    {
        return $this->dataPtsControl;
    }

    public function getDvbNitSettings(): ?DvbNitSettings
    {
        return $this->dvbNitSettings;
    }

    public function getDvbSdtSettings(): ?DvbSdtSettings
    {
        return $this->dvbSdtSettings;
    }

    /**
     * @return int[]
     */
    public function getDvbSubPids(): array
    {
        return $this->dvbSubPids ?? [];
    }

    public function getDvbTdtSettings(): ?DvbTdtSettings
    {
        return $this->dvbTdtSettings;
    }

    public function getDvbTeletextPid(): ?int
    {
        return $this->dvbTeletextPid;
    }

    /**
     * @return M2tsEbpAudioInterval::*|null
     */
    public function getEbpAudioInterval(): ?string
    {
        return $this->ebpAudioInterval;
    }

    /**
     * @return M2tsEbpPlacement::*|null
     */
    public function getEbpPlacement(): ?string
    {
        return $this->ebpPlacement;
    }

    /**
     * @return M2tsEsRateInPes::*|null
     */
    public function getEsRateInPes(): ?string
    {
        return $this->esRateInPes;
    }

    /**
     * @return M2tsForceTsVideoEbpOrder::*|null
     */
    public function getForceTsVideoEbpOrder(): ?string
    {
        return $this->forceTsVideoEbpOrder;
    }

    public function getFragmentTime(): ?float
    {
        return $this->fragmentTime;
    }

    /**
     * @return M2tsKlvMetadata::*|null
     */
    public function getKlvMetadata(): ?string
    {
        return $this->klvMetadata;
    }

    public function getMaxPcrInterval(): ?int
    {
        return $this->maxPcrInterval;
    }

    public function getMinEbpInterval(): ?int
    {
        return $this->minEbpInterval;
    }

    /**
     * @return M2tsNielsenId3::*|null
     */
    public function getNielsenId3(): ?string
    {
        return $this->nielsenId3;
    }

    public function getNullPacketBitrate(): ?float
    {
        return $this->nullPacketBitrate;
    }

    public function getPatInterval(): ?int
    {
        return $this->patInterval;
    }

    /**
     * @return M2tsPcrControl::*|null
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

    /**
     * @return M2tsRateMode::*|null
     */
    public function getRateMode(): ?string
    {
        return $this->rateMode;
    }

    public function getScte35Esam(): ?M2tsScte35Esam
    {
        return $this->scte35Esam;
    }

    public function getScte35Pid(): ?int
    {
        return $this->scte35Pid;
    }

    /**
     * @return M2tsScte35Source::*|null
     */
    public function getScte35Source(): ?string
    {
        return $this->scte35Source;
    }

    /**
     * @return M2tsSegmentationMarkers::*|null
     */
    public function getSegmentationMarkers(): ?string
    {
        return $this->segmentationMarkers;
    }

    /**
     * @return M2tsSegmentationStyle::*|null
     */
    public function getSegmentationStyle(): ?string
    {
        return $this->segmentationStyle;
    }

    public function getSegmentationTime(): ?float
    {
        return $this->segmentationTime;
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
        if (null !== $v = $this->audioBufferModel) {
            if (!M2tsAudioBufferModel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "audioBufferModel" for "%s". The value "%s" is not a valid "M2tsAudioBufferModel".', __CLASS__, $v));
            }
            $payload['audioBufferModel'] = $v;
        }
        if (null !== $v = $this->audioDuration) {
            if (!M2tsAudioDuration::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "audioDuration" for "%s". The value "%s" is not a valid "M2tsAudioDuration".', __CLASS__, $v));
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
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->bufferModel) {
            if (!M2tsBufferModel::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "bufferModel" for "%s". The value "%s" is not a valid "M2tsBufferModel".', __CLASS__, $v));
            }
            $payload['bufferModel'] = $v;
        }
        if (null !== $v = $this->dataPtsControl) {
            if (!M2tsDataPtsControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dataPTSControl" for "%s". The value "%s" is not a valid "M2tsDataPtsControl".', __CLASS__, $v));
            }
            $payload['dataPTSControl'] = $v;
        }
        if (null !== $v = $this->dvbNitSettings) {
            $payload['dvbNitSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->dvbSdtSettings) {
            $payload['dvbSdtSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->dvbSubPids) {
            $index = -1;
            $payload['dvbSubPids'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['dvbSubPids'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->dvbTdtSettings) {
            $payload['dvbTdtSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->dvbTeletextPid) {
            $payload['dvbTeletextPid'] = $v;
        }
        if (null !== $v = $this->ebpAudioInterval) {
            if (!M2tsEbpAudioInterval::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ebpAudioInterval" for "%s". The value "%s" is not a valid "M2tsEbpAudioInterval".', __CLASS__, $v));
            }
            $payload['ebpAudioInterval'] = $v;
        }
        if (null !== $v = $this->ebpPlacement) {
            if (!M2tsEbpPlacement::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ebpPlacement" for "%s". The value "%s" is not a valid "M2tsEbpPlacement".', __CLASS__, $v));
            }
            $payload['ebpPlacement'] = $v;
        }
        if (null !== $v = $this->esRateInPes) {
            if (!M2tsEsRateInPes::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "esRateInPes" for "%s". The value "%s" is not a valid "M2tsEsRateInPes".', __CLASS__, $v));
            }
            $payload['esRateInPes'] = $v;
        }
        if (null !== $v = $this->forceTsVideoEbpOrder) {
            if (!M2tsForceTsVideoEbpOrder::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "forceTsVideoEbpOrder" for "%s". The value "%s" is not a valid "M2tsForceTsVideoEbpOrder".', __CLASS__, $v));
            }
            $payload['forceTsVideoEbpOrder'] = $v;
        }
        if (null !== $v = $this->fragmentTime) {
            $payload['fragmentTime'] = $v;
        }
        if (null !== $v = $this->klvMetadata) {
            if (!M2tsKlvMetadata::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "klvMetadata" for "%s". The value "%s" is not a valid "M2tsKlvMetadata".', __CLASS__, $v));
            }
            $payload['klvMetadata'] = $v;
        }
        if (null !== $v = $this->maxPcrInterval) {
            $payload['maxPcrInterval'] = $v;
        }
        if (null !== $v = $this->minEbpInterval) {
            $payload['minEbpInterval'] = $v;
        }
        if (null !== $v = $this->nielsenId3) {
            if (!M2tsNielsenId3::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "nielsenId3" for "%s". The value "%s" is not a valid "M2tsNielsenId3".', __CLASS__, $v));
            }
            $payload['nielsenId3'] = $v;
        }
        if (null !== $v = $this->nullPacketBitrate) {
            $payload['nullPacketBitrate'] = $v;
        }
        if (null !== $v = $this->patInterval) {
            $payload['patInterval'] = $v;
        }
        if (null !== $v = $this->pcrControl) {
            if (!M2tsPcrControl::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "pcrControl" for "%s". The value "%s" is not a valid "M2tsPcrControl".', __CLASS__, $v));
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
        if (null !== $v = $this->rateMode) {
            if (!M2tsRateMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "rateMode" for "%s". The value "%s" is not a valid "M2tsRateMode".', __CLASS__, $v));
            }
            $payload['rateMode'] = $v;
        }
        if (null !== $v = $this->scte35Esam) {
            $payload['scte35Esam'] = $v->requestBody();
        }
        if (null !== $v = $this->scte35Pid) {
            $payload['scte35Pid'] = $v;
        }
        if (null !== $v = $this->scte35Source) {
            if (!M2tsScte35Source::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "scte35Source" for "%s". The value "%s" is not a valid "M2tsScte35Source".', __CLASS__, $v));
            }
            $payload['scte35Source'] = $v;
        }
        if (null !== $v = $this->segmentationMarkers) {
            if (!M2tsSegmentationMarkers::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "segmentationMarkers" for "%s". The value "%s" is not a valid "M2tsSegmentationMarkers".', __CLASS__, $v));
            }
            $payload['segmentationMarkers'] = $v;
        }
        if (null !== $v = $this->segmentationStyle) {
            if (!M2tsSegmentationStyle::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "segmentationStyle" for "%s". The value "%s" is not a valid "M2tsSegmentationStyle".', __CLASS__, $v));
            }
            $payload['segmentationStyle'] = $v;
        }
        if (null !== $v = $this->segmentationTime) {
            $payload['segmentationTime'] = $v;
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
