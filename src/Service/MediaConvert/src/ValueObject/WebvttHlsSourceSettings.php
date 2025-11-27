<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Settings specific to WebVTT sources in HLS alternative rendition group. Specify the properties (renditionGroupId,
 * renditionName or renditionLanguageCode) to identify the unique subtitle track among the alternative rendition groups
 * present in the HLS manifest. If no unique track is found, or multiple tracks match the specified properties, the job
 * fails. If there is only one subtitle track in the rendition group, the settings can be left empty and the default
 * subtitle track will be chosen. If your caption source is a sidecar file, use FileSourceSettings instead of
 * WebvttHlsSourceSettings.
 */
final class WebvttHlsSourceSettings
{
    /**
     * Optional. Specify alternative group ID.
     *
     * @var string|null
     */
    private $renditionGroupId;

    /**
     * Optionally specify the language, using an ISO 639-2 or ISO 639-3 three-letter code in all capital letters. You can
     * find a list of codes at: https://www.loc.gov/standards/iso639-2/php/code_list.php.
     *
     * @var LanguageCode::*|null
     */
    private $renditionLanguageCode;

    /**
     * Optional. Specify media name.
     *
     * @var string|null
     */
    private $renditionName;

    /**
     * @param array{
     *   RenditionGroupId?: string|null,
     *   RenditionLanguageCode?: LanguageCode::*|null,
     *   RenditionName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->renditionGroupId = $input['RenditionGroupId'] ?? null;
        $this->renditionLanguageCode = $input['RenditionLanguageCode'] ?? null;
        $this->renditionName = $input['RenditionName'] ?? null;
    }

    /**
     * @param array{
     *   RenditionGroupId?: string|null,
     *   RenditionLanguageCode?: LanguageCode::*|null,
     *   RenditionName?: string|null,
     * }|WebvttHlsSourceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRenditionGroupId(): ?string
    {
        return $this->renditionGroupId;
    }

    /**
     * @return LanguageCode::*|null
     */
    public function getRenditionLanguageCode(): ?string
    {
        return $this->renditionLanguageCode;
    }

    public function getRenditionName(): ?string
    {
        return $this->renditionName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->renditionGroupId) {
            $payload['renditionGroupId'] = $v;
        }
        if (null !== $v = $this->renditionLanguageCode) {
            if (!LanguageCode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "renditionLanguageCode" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['renditionLanguageCode'] = $v;
        }
        if (null !== $v = $this->renditionName) {
            $payload['renditionName'] = $v;
        }

        return $payload;
    }
}
