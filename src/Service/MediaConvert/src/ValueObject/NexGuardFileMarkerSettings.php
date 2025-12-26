<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\WatermarkingStrength;

/**
 * For forensic video watermarking, MediaConvert supports Nagra NexGuard File Marker watermarking. MediaConvert supports
 * both PreRelease Content (NGPR/G2) and OTT Streaming workflows.
 */
final class NexGuardFileMarkerSettings
{
    /**
     * Use the base64 license string that Nagra provides you. Enter it directly in your JSON job specification or in the
     * console. Required when you include Nagra NexGuard File Marker watermarking in your job.
     *
     * @var string|null
     */
    private $license;

    /**
     * Specify the payload ID that you want associated with this output. Valid values vary depending on your Nagra NexGuard
     * forensic watermarking workflow. Required when you include Nagra NexGuard File Marker watermarking in your job. For
     * PreRelease Content (NGPR/G2), specify an integer from 1 through 4,194,303. You must generate a unique ID for each
     * asset you watermark, and keep a record of which ID you have assigned to each asset. Neither Nagra nor MediaConvert
     * keep track of the relationship between output files and your IDs. For OTT Streaming, create two adaptive bitrate
     * (ABR) stacks for each asset. Do this by setting up two output groups. For one output group, set the value of Payload
     * ID to 0 in every output. For the other output group, set Payload ID to 1 in every output.
     *
     * @var int|null
     */
    private $payload;

    /**
     * Enter one of the watermarking preset strings that Nagra provides you. Required when you include Nagra NexGuard File
     * Marker watermarking in your job.
     *
     * @var string|null
     */
    private $preset;

    /**
     * Optional. Ignore this setting unless Nagra support directs you to specify a value. When you don't specify a value
     * here, the Nagra NexGuard library uses its default value.
     *
     * @var WatermarkingStrength::*|null
     */
    private $strength;

    /**
     * @param array{
     *   License?: string|null,
     *   Payload?: int|null,
     *   Preset?: string|null,
     *   Strength?: WatermarkingStrength::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->license = $input['License'] ?? null;
        $this->payload = $input['Payload'] ?? null;
        $this->preset = $input['Preset'] ?? null;
        $this->strength = $input['Strength'] ?? null;
    }

    /**
     * @param array{
     *   License?: string|null,
     *   Payload?: int|null,
     *   Preset?: string|null,
     *   Strength?: WatermarkingStrength::*|null,
     * }|NexGuardFileMarkerSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function getPayload(): ?int
    {
        return $this->payload;
    }

    public function getPreset(): ?string
    {
        return $this->preset;
    }

    /**
     * @return WatermarkingStrength::*|null
     */
    public function getStrength(): ?string
    {
        return $this->strength;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->license) {
            $payload['license'] = $v;
        }
        if (null !== $v = $this->payload) {
            $payload['payload'] = $v;
        }
        if (null !== $v = $this->preset) {
            $payload['preset'] = $v;
        }
        if (null !== $v = $this->strength) {
            if (!WatermarkingStrength::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "strength" for "%s". The value "%s" is not a valid "WatermarkingStrength".', __CLASS__, $v));
            }
            $payload['strength'] = $v;
        }

        return $payload;
    }
}
