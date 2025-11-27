<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\MsSmoothAudioDeduplication;
use AsyncAws\MediaConvert\Enum\MsSmoothFragmentLengthControl;
use AsyncAws\MediaConvert\Enum\MsSmoothManifestEncoding;

/**
 * Settings related to your Microsoft Smooth Streaming output package. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/outputs-file-ABR.html.
 */
final class MsSmoothGroupSettings
{
    /**
     * By default, the service creates one .ism Microsoft Smooth Streaming manifest for each Microsoft Smooth Streaming
     * output group in your job. This default manifest references every output in the output group. To create additional
     * manifests that reference a subset of the outputs in the output group, specify a list of them here.
     *
     * @var MsSmoothAdditionalManifest[]|null
     */
    private $additionalManifests;

    /**
     * COMBINE_DUPLICATE_STREAMS combines identical audio encoding settings across a Microsoft Smooth output group into a
     * single audio stream.
     *
     * @var MsSmoothAudioDeduplication::*|null
     */
    private $audioDeduplication;

    /**
     * Use Destination to specify the S3 output location and the output filename base. Destination accepts format
     * identifiers. If you do not specify the base filename in the URI, the service will use the filename of the input file.
     * If your job has multiple inputs, the service uses the filename of the first input file.
     *
     * @var string|null
     */
    private $destination;

    /**
     * Settings associated with the destination. Will vary based on the type of destination.
     *
     * @var DestinationSettings|null
     */
    private $destinationSettings;

    /**
     * If you are using DRM, set DRM System to specify the value SpekeKeyProvider.
     *
     * @var MsSmoothEncryptionSettings|null
     */
    private $encryption;

    /**
     * Specify how you want MediaConvert to determine the fragment length. Choose Exact to have the encoder use the exact
     * length that you specify with the setting Fragment length. This might result in extra I-frames. Choose Multiple of GOP
     * to have the encoder round up the segment lengths to match the next GOP boundary.
     *
     * @var int|null
     */
    private $fragmentLength;

    /**
     * Specify how you want MediaConvert to determine the fragment length. Choose Exact to have the encoder use the exact
     * length that you specify with the setting Fragment length. This might result in extra I-frames. Choose Multiple of GOP
     * to have the encoder round up the segment lengths to match the next GOP boundary.
     *
     * @var MsSmoothFragmentLengthControl::*|null
     */
    private $fragmentLengthControl;

    /**
     * Use Manifest encoding to specify the encoding format for the server and client manifest. Valid options are utf8 and
     * utf16.
     *
     * @var MsSmoothManifestEncoding::*|null
     */
    private $manifestEncoding;

    /**
     * @param array{
     *   AdditionalManifests?: array<MsSmoothAdditionalManifest|array>|null,
     *   AudioDeduplication?: MsSmoothAudioDeduplication::*|null,
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     *   Encryption?: MsSmoothEncryptionSettings|array|null,
     *   FragmentLength?: int|null,
     *   FragmentLengthControl?: MsSmoothFragmentLengthControl::*|null,
     *   ManifestEncoding?: MsSmoothManifestEncoding::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->additionalManifests = isset($input['AdditionalManifests']) ? array_map([MsSmoothAdditionalManifest::class, 'create'], $input['AdditionalManifests']) : null;
        $this->audioDeduplication = $input['AudioDeduplication'] ?? null;
        $this->destination = $input['Destination'] ?? null;
        $this->destinationSettings = isset($input['DestinationSettings']) ? DestinationSettings::create($input['DestinationSettings']) : null;
        $this->encryption = isset($input['Encryption']) ? MsSmoothEncryptionSettings::create($input['Encryption']) : null;
        $this->fragmentLength = $input['FragmentLength'] ?? null;
        $this->fragmentLengthControl = $input['FragmentLengthControl'] ?? null;
        $this->manifestEncoding = $input['ManifestEncoding'] ?? null;
    }

    /**
     * @param array{
     *   AdditionalManifests?: array<MsSmoothAdditionalManifest|array>|null,
     *   AudioDeduplication?: MsSmoothAudioDeduplication::*|null,
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     *   Encryption?: MsSmoothEncryptionSettings|array|null,
     *   FragmentLength?: int|null,
     *   FragmentLengthControl?: MsSmoothFragmentLengthControl::*|null,
     *   ManifestEncoding?: MsSmoothManifestEncoding::*|null,
     * }|MsSmoothGroupSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MsSmoothAdditionalManifest[]
     */
    public function getAdditionalManifests(): array
    {
        return $this->additionalManifests ?? [];
    }

    /**
     * @return MsSmoothAudioDeduplication::*|null
     */
    public function getAudioDeduplication(): ?string
    {
        return $this->audioDeduplication;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getDestinationSettings(): ?DestinationSettings
    {
        return $this->destinationSettings;
    }

    public function getEncryption(): ?MsSmoothEncryptionSettings
    {
        return $this->encryption;
    }

    public function getFragmentLength(): ?int
    {
        return $this->fragmentLength;
    }

    /**
     * @return MsSmoothFragmentLengthControl::*|null
     */
    public function getFragmentLengthControl(): ?string
    {
        return $this->fragmentLengthControl;
    }

    /**
     * @return MsSmoothManifestEncoding::*|null
     */
    public function getManifestEncoding(): ?string
    {
        return $this->manifestEncoding;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->additionalManifests) {
            $index = -1;
            $payload['additionalManifests'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['additionalManifests'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->audioDeduplication) {
            if (!MsSmoothAudioDeduplication::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDeduplication" for "%s". The value "%s" is not a valid "MsSmoothAudioDeduplication".', __CLASS__, $v));
            }
            $payload['audioDeduplication'] = $v;
        }
        if (null !== $v = $this->destination) {
            $payload['destination'] = $v;
        }
        if (null !== $v = $this->destinationSettings) {
            $payload['destinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->encryption) {
            $payload['encryption'] = $v->requestBody();
        }
        if (null !== $v = $this->fragmentLength) {
            $payload['fragmentLength'] = $v;
        }
        if (null !== $v = $this->fragmentLengthControl) {
            if (!MsSmoothFragmentLengthControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "fragmentLengthControl" for "%s". The value "%s" is not a valid "MsSmoothFragmentLengthControl".', __CLASS__, $v));
            }
            $payload['fragmentLengthControl'] = $v;
        }
        if (null !== $v = $this->manifestEncoding) {
            if (!MsSmoothManifestEncoding::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "manifestEncoding" for "%s". The value "%s" is not a valid "MsSmoothManifestEncoding".', __CLASS__, $v));
            }
            $payload['manifestEncoding'] = $v;
        }

        return $payload;
    }
}
