<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings specific to caption sources that are specified by track number. Currently, this is only IMSC captions in an
 * IMF package. If your caption source is IMSC 1.1 in a separate xml file, use FileSourceSettings instead of
 * TrackSourceSettings.
 */
final class TrackSourceSettings
{
    /**
     * Use this setting to select a single captions track from a source. Track numbers correspond to the order in the
     * captions source file. For IMF sources, track numbering is based on the order that the captions appear in the CPL. For
     * example, use 1 to select the captions asset that is listed first in the CPL. To include more than one captions track
     * in your job outputs, create multiple input captions selectors. Specify one track per selector.
     *
     * @var int|null
     */
    private $trackNumber;

    /**
     * @param array{
     *   TrackNumber?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->trackNumber = $input['TrackNumber'] ?? null;
    }

    /**
     * @param array{
     *   TrackNumber?: int|null,
     * }|TrackSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTrackNumber(): ?int
    {
        return $this->trackNumber;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->trackNumber) {
            $payload['trackNumber'] = $v;
        }

        return $payload;
    }
}
