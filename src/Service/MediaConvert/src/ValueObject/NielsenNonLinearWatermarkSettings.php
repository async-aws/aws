<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\NielsenActiveWatermarkProcessType;
use AsyncAws\MediaConvert\Enum\NielsenSourceWatermarkStatusType;
use AsyncAws\MediaConvert\Enum\NielsenUniqueTicPerAudioTrackType;

/**
 * Ignore these settings unless you are using Nielsen non-linear watermarking. Specify the values that MediaConvert uses
 * to generate and place Nielsen watermarks in your output audio. In addition to specifying these values, you also need
 * to set up your cloud TIC server. These settings apply to every output in your job. The MediaConvert implementation is
 * currently with the following Nielsen versions: Nielsen Watermark SDK Version 6.0.13 Nielsen NLM Watermark Engine
 * Version 1.3.3 Nielsen Watermark Authenticator [SID_TIC] Version [7.0.0].
 */
final class NielsenNonLinearWatermarkSettings
{
    /**
     * Choose the type of Nielsen watermarks that you want in your outputs. When you choose NAES 2 and NW, you must provide
     * a value for the setting SID. When you choose CBET, you must provide a value for the setting CSID. When you choose
     * NAES 2, NW, and CBET, you must provide values for both of these settings.
     *
     * @var NielsenActiveWatermarkProcessType::*|null
     */
    private $activeWatermarkProcess;

    /**
     * Optional. Use this setting when you want the service to include an ADI file in the Nielsen metadata .zip file. To
     * provide an ADI file, store it in Amazon S3 and provide a URL to it here. The URL should be in the following format:
     * S3://bucket/path/ADI-file. For more information about the metadata .zip file, see the setting Metadata destination.
     *
     * @var string|null
     */
    private $adiFilename;

    /**
     * Use the asset ID that you provide to Nielsen to uniquely identify this asset. Required for all Nielsen non-linear
     * watermarking.
     *
     * @var string|null
     */
    private $assetId;

    /**
     * Use the asset name that you provide to Nielsen for this asset. Required for all Nielsen non-linear watermarking.
     *
     * @var string|null
     */
    private $assetName;

    /**
     * Use the CSID that Nielsen provides to you. This CBET source ID should be unique to your Nielsen account but common to
     * all of your output assets that have CBET watermarking. Required when you choose a value for the setting Watermark
     * types that includes CBET.
     *
     * @var string|null
     */
    private $cbetSourceId;

    /**
     * Optional. If this asset uses an episode ID with Nielsen, provide it here.
     *
     * @var string|null
     */
    private $episodeId;

    /**
     * Specify the Amazon S3 location where you want MediaConvert to save your Nielsen non-linear metadata .zip file. This
     * Amazon S3 bucket must be in the same Region as the one where you do your MediaConvert transcoding. If you want to
     * include an ADI file in this .zip file, use the setting ADI file to specify it. MediaConvert delivers the Nielsen
     * metadata .zip files only to your metadata destination Amazon S3 bucket. It doesn't deliver the .zip files to Nielsen.
     * You are responsible for delivering the metadata .zip files to Nielsen.
     *
     * @var string|null
     */
    private $metadataDestination;

    /**
     * Use the SID that Nielsen provides to you. This source ID should be unique to your Nielsen account but common to all
     * of your output assets. Required for all Nielsen non-linear watermarking. This ID should be unique to your Nielsen
     * account but common to all of your output assets. Required for all Nielsen non-linear watermarking.
     *
     * @var int|null
     */
    private $sourceId;

    /**
     * Required. Specify whether your source content already contains Nielsen non-linear watermarks. When you set this value
     * to Watermarked, the service fails the job. Nielsen requires that you add non-linear watermarking to only clean
     * content that doesn't already have non-linear Nielsen watermarks.
     *
     * @var NielsenSourceWatermarkStatusType::*|null
     */
    private $sourceWatermarkStatus;

    /**
     * Specify the endpoint for the TIC server that you have deployed and configured in the AWS Cloud. Required for all
     * Nielsen non-linear watermarking. MediaConvert can't connect directly to a TIC server. Instead, you must use API
     * Gateway to provide a RESTful interface between MediaConvert and a TIC server that you deploy in your AWS account. For
     * more information on deploying a TIC server in your AWS account and the required API Gateway, contact Nielsen support.
     *
     * @var string|null
     */
    private $ticServerUrl;

    /**
     * To create assets that have the same TIC values in each audio track, keep the default value Share TICs. To create
     * assets that have unique TIC values for each audio track, choose Use unique TICs.
     *
     * @var NielsenUniqueTicPerAudioTrackType::*|null
     */
    private $uniqueTicPerAudioTrack;

    /**
     * @param array{
     *   ActiveWatermarkProcess?: NielsenActiveWatermarkProcessType::*|null,
     *   AdiFilename?: string|null,
     *   AssetId?: string|null,
     *   AssetName?: string|null,
     *   CbetSourceId?: string|null,
     *   EpisodeId?: string|null,
     *   MetadataDestination?: string|null,
     *   SourceId?: int|null,
     *   SourceWatermarkStatus?: NielsenSourceWatermarkStatusType::*|null,
     *   TicServerUrl?: string|null,
     *   UniqueTicPerAudioTrack?: NielsenUniqueTicPerAudioTrackType::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->activeWatermarkProcess = $input['ActiveWatermarkProcess'] ?? null;
        $this->adiFilename = $input['AdiFilename'] ?? null;
        $this->assetId = $input['AssetId'] ?? null;
        $this->assetName = $input['AssetName'] ?? null;
        $this->cbetSourceId = $input['CbetSourceId'] ?? null;
        $this->episodeId = $input['EpisodeId'] ?? null;
        $this->metadataDestination = $input['MetadataDestination'] ?? null;
        $this->sourceId = $input['SourceId'] ?? null;
        $this->sourceWatermarkStatus = $input['SourceWatermarkStatus'] ?? null;
        $this->ticServerUrl = $input['TicServerUrl'] ?? null;
        $this->uniqueTicPerAudioTrack = $input['UniqueTicPerAudioTrack'] ?? null;
    }

    /**
     * @param array{
     *   ActiveWatermarkProcess?: NielsenActiveWatermarkProcessType::*|null,
     *   AdiFilename?: string|null,
     *   AssetId?: string|null,
     *   AssetName?: string|null,
     *   CbetSourceId?: string|null,
     *   EpisodeId?: string|null,
     *   MetadataDestination?: string|null,
     *   SourceId?: int|null,
     *   SourceWatermarkStatus?: NielsenSourceWatermarkStatusType::*|null,
     *   TicServerUrl?: string|null,
     *   UniqueTicPerAudioTrack?: NielsenUniqueTicPerAudioTrackType::*|null,
     * }|NielsenNonLinearWatermarkSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return NielsenActiveWatermarkProcessType::*|null
     */
    public function getActiveWatermarkProcess(): ?string
    {
        return $this->activeWatermarkProcess;
    }

    public function getAdiFilename(): ?string
    {
        return $this->adiFilename;
    }

    public function getAssetId(): ?string
    {
        return $this->assetId;
    }

    public function getAssetName(): ?string
    {
        return $this->assetName;
    }

    public function getCbetSourceId(): ?string
    {
        return $this->cbetSourceId;
    }

    public function getEpisodeId(): ?string
    {
        return $this->episodeId;
    }

    public function getMetadataDestination(): ?string
    {
        return $this->metadataDestination;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    /**
     * @return NielsenSourceWatermarkStatusType::*|null
     */
    public function getSourceWatermarkStatus(): ?string
    {
        return $this->sourceWatermarkStatus;
    }

    public function getTicServerUrl(): ?string
    {
        return $this->ticServerUrl;
    }

    /**
     * @return NielsenUniqueTicPerAudioTrackType::*|null
     */
    public function getUniqueTicPerAudioTrack(): ?string
    {
        return $this->uniqueTicPerAudioTrack;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->activeWatermarkProcess) {
            if (!NielsenActiveWatermarkProcessType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "activeWatermarkProcess" for "%s". The value "%s" is not a valid "NielsenActiveWatermarkProcessType".', __CLASS__, $v));
            }
            $payload['activeWatermarkProcess'] = $v;
        }
        if (null !== $v = $this->adiFilename) {
            $payload['adiFilename'] = $v;
        }
        if (null !== $v = $this->assetId) {
            $payload['assetId'] = $v;
        }
        if (null !== $v = $this->assetName) {
            $payload['assetName'] = $v;
        }
        if (null !== $v = $this->cbetSourceId) {
            $payload['cbetSourceId'] = $v;
        }
        if (null !== $v = $this->episodeId) {
            $payload['episodeId'] = $v;
        }
        if (null !== $v = $this->metadataDestination) {
            $payload['metadataDestination'] = $v;
        }
        if (null !== $v = $this->sourceId) {
            $payload['sourceId'] = $v;
        }
        if (null !== $v = $this->sourceWatermarkStatus) {
            if (!NielsenSourceWatermarkStatusType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sourceWatermarkStatus" for "%s". The value "%s" is not a valid "NielsenSourceWatermarkStatusType".', __CLASS__, $v));
            }
            $payload['sourceWatermarkStatus'] = $v;
        }
        if (null !== $v = $this->ticServerUrl) {
            $payload['ticServerUrl'] = $v;
        }
        if (null !== $v = $this->uniqueTicPerAudioTrack) {
            if (!NielsenUniqueTicPerAudioTrackType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "uniqueTicPerAudioTrack" for "%s". The value "%s" is not a valid "NielsenUniqueTicPerAudioTrackType".', __CLASS__, $v));
            }
            $payload['uniqueTicPerAudioTrack'] = $v;
        }

        return $payload;
    }
}
