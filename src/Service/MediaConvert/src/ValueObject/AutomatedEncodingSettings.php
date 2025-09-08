<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use automated encoding to have MediaConvert choose your encoding settings for you, based on characteristics of your
 * input video.
 */
final class AutomatedEncodingSettings
{
    /**
     * Use automated ABR to have MediaConvert set up the renditions in your ABR package for you automatically, based on
     * characteristics of your input video. This feature optimizes video quality while minimizing the overall size of your
     * ABR package.
     *
     * @var AutomatedAbrSettings|null
     */
    private $abrSettings;

    /**
     * @param array{
     *   AbrSettings?: AutomatedAbrSettings|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->abrSettings = isset($input['AbrSettings']) ? AutomatedAbrSettings::create($input['AbrSettings']) : null;
    }

    /**
     * @param array{
     *   AbrSettings?: AutomatedAbrSettings|array|null,
     * }|AutomatedEncodingSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAbrSettings(): ?AutomatedAbrSettings
    {
        return $this->abrSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->abrSettings) {
            $payload['abrSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
