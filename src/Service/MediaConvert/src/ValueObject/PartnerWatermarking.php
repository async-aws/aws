<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * If you work with a third party video watermarking partner, use the group of settings that correspond with your
 * watermarking partner to include watermarks in your output.
 */
final class PartnerWatermarking
{
    /**
     * For forensic video watermarking, MediaConvert supports Nagra NexGuard File Marker watermarking. MediaConvert supports
     * both PreRelease Content (NGPR/G2) and OTT Streaming workflows.
     *
     * @var NexGuardFileMarkerSettings|null
     */
    private $nexguardFileMarkerSettings;

    /**
     * @param array{
     *   NexguardFileMarkerSettings?: NexGuardFileMarkerSettings|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->nexguardFileMarkerSettings = isset($input['NexguardFileMarkerSettings']) ? NexGuardFileMarkerSettings::create($input['NexguardFileMarkerSettings']) : null;
    }

    /**
     * @param array{
     *   NexguardFileMarkerSettings?: NexGuardFileMarkerSettings|array|null,
     * }|PartnerWatermarking $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNexguardFileMarkerSettings(): ?NexGuardFileMarkerSettings
    {
        return $this->nexguardFileMarkerSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->nexguardFileMarkerSettings) {
            $payload['nexguardFileMarkerSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
