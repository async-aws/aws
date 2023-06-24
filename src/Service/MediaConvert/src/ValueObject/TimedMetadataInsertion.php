<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Insert user-defined custom ID3 metadata (id3) at timecodes (timecode) that you specify. In each output that you want
 * to include this metadata, you must set ID3 metadata (timedMetadata) to Passthrough (PASSTHROUGH).
 */
final class TimedMetadataInsertion
{
    /**
     * Id3Insertions contains the array of Id3Insertion instances.
     */
    private $id3Insertions;

    /**
     * @param array{
     *   Id3Insertions?: null|Id3Insertion[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id3Insertions = isset($input['Id3Insertions']) ? array_map([Id3Insertion::class, 'create'], $input['Id3Insertions']) : null;
    }

    /**
     * @param array{
     *   Id3Insertions?: null|Id3Insertion[],
     * }|TimedMetadataInsertion $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Id3Insertion[]
     */
    public function getId3Insertions(): array
    {
        return $this->id3Insertions ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->id3Insertions) {
            $index = -1;
            $payload['id3Insertions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['id3Insertions'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
