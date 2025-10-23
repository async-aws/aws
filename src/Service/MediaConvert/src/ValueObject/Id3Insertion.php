<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * To insert ID3 tags in your output, specify two values. Use ID3 tag to specify the base 64 encoded string and use
 * Timecode to specify the time when the tag should be inserted. To insert multiple ID3 tags in your output, create
 * multiple instances of ID3 insertion.
 */
final class Id3Insertion
{
    /**
     * Use ID3 tag to provide a fully formed ID3 tag in base64-encode format.
     *
     * @var string|null
     */
    private $id3;

    /**
     * Provide a Timecode in HH:MM:SS:FF or HH:MM:SS;FF format.
     *
     * @var string|null
     */
    private $timecode;

    /**
     * @param array{
     *   Id3?: string|null,
     *   Timecode?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id3 = $input['Id3'] ?? null;
        $this->timecode = $input['Timecode'] ?? null;
    }

    /**
     * @param array{
     *   Id3?: string|null,
     *   Timecode?: string|null,
     * }|Id3Insertion $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId3(): ?string
    {
        return $this->id3;
    }

    public function getTimecode(): ?string
    {
        return $this->timecode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->id3) {
            $payload['id3'] = $v;
        }
        if (null !== $v = $this->timecode) {
            $payload['timecode'] = $v;
        }

        return $payload;
    }
}
