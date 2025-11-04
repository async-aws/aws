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
     * Use this setting to select a single captions track from a source. Stream numbers include all tracks in the source
     * file, regardless of type, and correspond to either the order of tracks in the file, or if applicable, the stream
     * number metadata of the track. Although all tracks count toward these stream numbers, in this caption selector
     * context, only the stream number of a track containing caption data may be used. To include more than one captions
     * track in your job outputs, create multiple input captions selectors. Specify one stream per selector. If your source
     * file contains a track which is not recognized by the service, then the corresponding stream number will still be
     * reserved for future use. If more types of caption data get recognized in the future, these numberings will not shift.
     *
     * @var int|null
     */
    private $streamNumber;

    /**
     * Use this setting to select a single captions track from a source. Track numbers correspond to the order in the
     * captions source file. For IMF sources, track numbering is based on the order that the captions appear in the CPL. For
     * example, use 1 to select the captions asset that is listed first in the CPL. To include more than one captions track
     * in your job outputs, create multiple input captions selectors. Specify one track per selector. If more types of
     * caption data get recognized in the future, these numberings may shift, but the numberings used for streamNumber will
     * not.
     *
     * @var int|null
     */
    private $trackNumber;

    /**
     * @param array{
     *   StreamNumber?: int|null,
     *   TrackNumber?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->streamNumber = $input['StreamNumber'] ?? null;
        $this->trackNumber = $input['TrackNumber'] ?? null;
    }

    /**
     * @param array{
     *   StreamNumber?: int|null,
     *   TrackNumber?: int|null,
     * }|TrackSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStreamNumber(): ?int
    {
        return $this->streamNumber;
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
        if (null !== $v = $this->streamNumber) {
            $payload['streamNumber'] = $v;
        }
        if (null !== $v = $this->trackNumber) {
            $payload['trackNumber'] = $v;
        }

        return $payload;
    }
}
