<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * When you include Video generator, MediaConvert creates a video input with black frames. Use this setting if you do
 * not have a video input or if you want to add black video frames before, or after, other inputs. You can specify Video
 * generator, or you can specify an Input file, but you cannot specify both. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/video-generator.html.
 */
final class InputVideoGenerator
{
    /**
     * Specify an integer value for Black video duration from 50 to 86400000 to generate a black video input for that many
     * milliseconds. Required when you include Video generator.
     *
     * @var int|null
     */
    private $duration;

    /**
     * @param array{
     *   Duration?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->duration = $input['Duration'] ?? null;
    }

    /**
     * @param array{
     *   Duration?: null|int,
     * }|InputVideoGenerator $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->duration) {
            $payload['duration'] = $v;
        }

        return $payload;
    }
}
