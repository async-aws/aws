<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AdvancedInputFilterAddTexture;
use AsyncAws\MediaConvert\Enum\AdvancedInputFilterSharpen;

/**
 * Optional settings for Advanced input filter when you set Advanced input filter to Enabled.
 */
final class AdvancedInputFilterSettings
{
    /**
     * Add texture and detail to areas of your input video content that were lost after applying the Advanced input filter.
     * To adaptively add texture and reduce softness: Choose Enabled. To not add any texture: Keep the default value,
     * Disabled. We recommend that you choose Disabled for input video content that doesn't have texture, including screen
     * recordings, computer graphics, or cartoons.
     *
     * @var AdvancedInputFilterAddTexture::*|null
     */
    private $addTexture;

    /**
     * Optionally specify the amount of sharpening to apply when you use the Advanced input filter. Sharpening adds contrast
     * to the edges of your video content and can reduce softness. To apply no sharpening: Keep the default value, Off. To
     * apply a minimal amount of sharpening choose Low, or for the maximum choose High.
     *
     * @var AdvancedInputFilterSharpen::*|null
     */
    private $sharpening;

    /**
     * @param array{
     *   AddTexture?: AdvancedInputFilterAddTexture::*|null,
     *   Sharpening?: AdvancedInputFilterSharpen::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->addTexture = $input['AddTexture'] ?? null;
        $this->sharpening = $input['Sharpening'] ?? null;
    }

    /**
     * @param array{
     *   AddTexture?: AdvancedInputFilterAddTexture::*|null,
     *   Sharpening?: AdvancedInputFilterSharpen::*|null,
     * }|AdvancedInputFilterSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AdvancedInputFilterAddTexture::*|null
     */
    public function getAddTexture(): ?string
    {
        return $this->addTexture;
    }

    /**
     * @return AdvancedInputFilterSharpen::*|null
     */
    public function getSharpening(): ?string
    {
        return $this->sharpening;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->addTexture) {
            if (!AdvancedInputFilterAddTexture::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "addTexture" for "%s". The value "%s" is not a valid "AdvancedInputFilterAddTexture".', __CLASS__, $v));
            }
            $payload['addTexture'] = $v;
        }
        if (null !== $v = $this->sharpening) {
            if (!AdvancedInputFilterSharpen::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sharpening" for "%s". The value "%s" is not a valid "AdvancedInputFilterSharpen".', __CLASS__, $v));
            }
            $payload['sharpening'] = $v;
        }

        return $payload;
    }
}
