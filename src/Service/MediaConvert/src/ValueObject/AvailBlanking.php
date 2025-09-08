<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use ad avail blanking settings to specify your output content during SCTE-35 triggered ad avails. You can blank your
 * video or overlay it with an image. MediaConvert also removes any audio and embedded captions during the ad avail. For
 * more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/ad-avail-blanking.html.
 */
final class AvailBlanking
{
    /**
     * Blanking image to be used. Leave empty for solid black. Only bmp and png images are supported.
     *
     * @var string|null
     */
    private $availBlankingImage;

    /**
     * @param array{
     *   AvailBlankingImage?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->availBlankingImage = $input['AvailBlankingImage'] ?? null;
    }

    /**
     * @param array{
     *   AvailBlankingImage?: string|null,
     * }|AvailBlanking $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAvailBlankingImage(): ?string
    {
        return $this->availBlankingImage;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->availBlankingImage) {
            $payload['availBlankingImage'] = $v;
        }

        return $payload;
    }
}
