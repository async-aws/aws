<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specific settings for this type of output.
 */
final class OutputSettings
{
    /**
     * Settings for HLS output groups.
     *
     * @var HlsSettings|null
     */
    private $hlsSettings;

    /**
     * @param array{
     *   HlsSettings?: HlsSettings|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->hlsSettings = isset($input['HlsSettings']) ? HlsSettings::create($input['HlsSettings']) : null;
    }

    /**
     * @param array{
     *   HlsSettings?: HlsSettings|array|null,
     * }|OutputSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHlsSettings(): ?HlsSettings
    {
        return $this->hlsSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->hlsSettings) {
            $payload['hlsSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
