<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings related to your File output group. MediaConvert uses this group of settings to generate a single standalone
 * file, rather than a streaming package.
 */
final class FileGroupSettings
{
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
     * @param array{
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->destination = $input['Destination'] ?? null;
        $this->destinationSettings = isset($input['DestinationSettings']) ? DestinationSettings::create($input['DestinationSettings']) : null;
    }

    /**
     * @param array{
     *   Destination?: string|null,
     *   DestinationSettings?: DestinationSettings|array|null,
     * }|FileGroupSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getDestinationSettings(): ?DestinationSettings
    {
        return $this->destinationSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->destination) {
            $payload['destination'] = $v;
        }
        if (null !== $v = $this->destinationSettings) {
            $payload['destinationSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
