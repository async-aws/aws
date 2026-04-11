<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specify the enhancement layer input video file path for Multi View outputs. The base layer input is treated as the
 * left eye and this Multi View input is treated as the right eye. Only one Multi View input is currently supported.
 * MediaConvert encodes both views into a single MV-HEVC output codec. When you add MultiViewSettings to your job, you
 * can only produce Multi View outputs. Adding any other codec output to the same job is not supported.
 */
final class MultiViewSettings
{
    /**
     * Input settings for MultiView Settings. You can include exactly one input as enhancement layer.
     *
     * @var MultiViewInput|null
     */
    private $input;

    /**
     * @param array{
     *   Input?: MultiViewInput|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->input = isset($input['Input']) ? MultiViewInput::create($input['Input']) : null;
    }

    /**
     * @param array{
     *   Input?: MultiViewInput|array|null,
     * }|MultiViewSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInput(): ?MultiViewInput
    {
        return $this->input;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->input) {
            $payload['input'] = $v->requestBody();
        }

        return $payload;
    }
}
