<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings to set up encryption with a static key provider.
 */
final class StaticKeyProvider
{
    /**
     * Relates to DRM implementation. Sets the value of the KEYFORMAT attribute. Must be 'identity' or a reverse DNS string.
     * May be omitted to indicate an implicit value of 'identity'.
     */
    private $keyFormat;

    /**
     * Relates to DRM implementation. Either a single positive integer version value or a slash delimited list of version
     * values (1/2/3).
     */
    private $keyFormatVersions;

    /**
     * Relates to DRM implementation. Use a 32-character hexidecimal string to specify Key Value (StaticKeyValue).
     */
    private $staticKeyValue;

    /**
     * Relates to DRM implementation. The location of the license server used for protecting content.
     */
    private $url;

    /**
     * @param array{
     *   KeyFormat?: null|string,
     *   KeyFormatVersions?: null|string,
     *   StaticKeyValue?: null|string,
     *   Url?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->keyFormat = $input['KeyFormat'] ?? null;
        $this->keyFormatVersions = $input['KeyFormatVersions'] ?? null;
        $this->staticKeyValue = $input['StaticKeyValue'] ?? null;
        $this->url = $input['Url'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKeyFormat(): ?string
    {
        return $this->keyFormat;
    }

    public function getKeyFormatVersions(): ?string
    {
        return $this->keyFormatVersions;
    }

    public function getStaticKeyValue(): ?string
    {
        return $this->staticKeyValue;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->keyFormat) {
            $payload['keyFormat'] = $v;
        }
        if (null !== $v = $this->keyFormatVersions) {
            $payload['keyFormatVersions'] = $v;
        }
        if (null !== $v = $this->staticKeyValue) {
            $payload['staticKeyValue'] = $v;
        }
        if (null !== $v = $this->url) {
            $payload['url'] = $v;
        }

        return $payload;
    }
}
