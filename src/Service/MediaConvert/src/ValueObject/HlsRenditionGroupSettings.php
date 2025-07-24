<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Settings specific to audio sources in an HLS alternate rendition group. Specify the properties (renditionGroupId,
 * renditionName or renditionLanguageCode) to identify the unique audio track among the alternative rendition groups
 * present in the HLS manifest. If no unique track is found, or multiple tracks match the properties provided, the job
 * fails. If no properties in hlsRenditionGroupSettings are specified, the default audio track within the video segment
 * is chosen. If there is no audio within video segment, the alternative audio with DEFAULT=YES is chosen instead.
 */
final class HlsRenditionGroupSettings
{
    /**
     * Optional. Specify alternative group ID.
     *
     * @var string|null
     */
    private $renditionGroupId;

    /**
     * Optional. Specify ISO 639-2 or ISO 639-3 code in the language property.
     *
     * @var LanguageCode::*|string|null
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
     *   RenditionGroupId?: null|string,
     *   RenditionLanguageCode?: null|LanguageCode::*|string,
     *   RenditionName?: null|string,
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
     *   RenditionGroupId?: null|string,
     *   RenditionLanguageCode?: null|LanguageCode::*|string,
     *   RenditionName?: null|string,
     * }|HlsRenditionGroupSettings $input
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
     * @return LanguageCode::*|string|null
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
