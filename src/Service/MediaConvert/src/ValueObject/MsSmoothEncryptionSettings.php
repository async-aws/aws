<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * If you are using DRM, set DRM System to specify the value SpekeKeyProvider.
 */
final class MsSmoothEncryptionSettings
{
    /**
     * If your output group type is HLS, DASH, or Microsoft Smooth, use these settings when doing DRM encryption with a
     * SPEKE-compliant key provider. If your output group type is CMAF, use the SpekeKeyProviderCmaf settings instead.
     *
     * @var SpekeKeyProvider|null
     */
    private $spekeKeyProvider;

    /**
     * @param array{
     *   SpekeKeyProvider?: SpekeKeyProvider|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->spekeKeyProvider = isset($input['SpekeKeyProvider']) ? SpekeKeyProvider::create($input['SpekeKeyProvider']) : null;
    }

    /**
     * @param array{
     *   SpekeKeyProvider?: SpekeKeyProvider|array|null,
     * }|MsSmoothEncryptionSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSpekeKeyProvider(): ?SpekeKeyProvider
    {
        return $this->spekeKeyProvider;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->spekeKeyProvider) {
            $payload['spekeKeyProvider'] = $v->requestBody();
        }

        return $payload;
    }
}
