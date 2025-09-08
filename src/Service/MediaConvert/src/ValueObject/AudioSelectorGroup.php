<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use audio selector groups to combine multiple sidecar audio inputs so that you can assign them to a single output
 * audio tab. Note that, if you're working with embedded audio, it's simpler to assign multiple input tracks into a
 * single audio selector rather than use an audio selector group.
 */
final class AudioSelectorGroup
{
    /**
     * Name of an Audio Selector within the same input to include in the group. Audio selector names are standardized, based
     * on their order within the input (e.g., "Audio Selector 1"). The audio selector name parameter can be repeated to add
     * any number of audio selectors to the group.
     *
     * @var string[]|null
     */
    private $audioSelectorNames;

    /**
     * @param array{
     *   AudioSelectorNames?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioSelectorNames = $input['AudioSelectorNames'] ?? null;
    }

    /**
     * @param array{
     *   AudioSelectorNames?: string[]|null,
     * }|AudioSelectorGroup $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAudioSelectorNames(): array
    {
        return $this->audioSelectorNames ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioSelectorNames) {
            $index = -1;
            $payload['audioSelectorNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['audioSelectorNames'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
