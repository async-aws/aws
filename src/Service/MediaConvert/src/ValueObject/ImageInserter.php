<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use the image inserter feature to include a graphic overlay on your video. Enable or disable this feature for each
 * input or output individually. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/graphic-overlay.html. This setting is disabled by default.
 */
final class ImageInserter
{
    /**
     * Specify the images that you want to overlay on your video. The images must be PNG or TGA files.
     *
     * @var InsertableImage[]|null
     */
    private $insertableImages;

    /**
     * Specify the reference white level, in nits, for all of your image inserter images. Use to correct brightness levels
     * within HDR10 outputs. For 1,000 nit peak brightness displays, we recommend that you set SDR reference white level to
     * 203 (according to ITU-R BT.2408). Leave blank to use the default value of 100, or specify an integer from 100 to
     * 1000.
     *
     * @var int|null
     */
    private $sdrReferenceWhiteLevel;

    /**
     * @param array{
     *   InsertableImages?: array<InsertableImage|array>|null,
     *   SdrReferenceWhiteLevel?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->insertableImages = isset($input['InsertableImages']) ? array_map([InsertableImage::class, 'create'], $input['InsertableImages']) : null;
        $this->sdrReferenceWhiteLevel = $input['SdrReferenceWhiteLevel'] ?? null;
    }

    /**
     * @param array{
     *   InsertableImages?: array<InsertableImage|array>|null,
     *   SdrReferenceWhiteLevel?: int|null,
     * }|ImageInserter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InsertableImage[]
     */
    public function getInsertableImages(): array
    {
        return $this->insertableImages ?? [];
    }

    public function getSdrReferenceWhiteLevel(): ?int
    {
        return $this->sdrReferenceWhiteLevel;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->insertableImages) {
            $index = -1;
            $payload['insertableImages'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['insertableImages'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->sdrReferenceWhiteLevel) {
            $payload['sdrReferenceWhiteLevel'] = $v;
        }

        return $payload;
    }
}
