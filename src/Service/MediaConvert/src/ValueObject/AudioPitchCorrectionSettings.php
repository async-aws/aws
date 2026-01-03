<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\SlowPalPitchCorrection;

/**
 * Settings for audio pitch correction during framerate conversion.
 */
final class AudioPitchCorrectionSettings
{
    /**
     * Use Slow PAL pitch correction to compensate for audio pitch changes during slow PAL frame rate conversion. This
     * setting only applies when Slow PAL is enabled in your output video codec settings. To automatically apply audio pitch
     * correction: Choose Enabled. MediaConvert automatically applies a pitch correction to your output to match the
     * original content's audio pitch. To not apply audio pitch correction: Keep the default value, Disabled.
     *
     * @var SlowPalPitchCorrection::*|null
     */
    private $slowPalPitchCorrection;

    /**
     * @param array{
     *   SlowPalPitchCorrection?: SlowPalPitchCorrection::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->slowPalPitchCorrection = $input['SlowPalPitchCorrection'] ?? null;
    }

    /**
     * @param array{
     *   SlowPalPitchCorrection?: SlowPalPitchCorrection::*|null,
     * }|AudioPitchCorrectionSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SlowPalPitchCorrection::*|null
     */
    public function getSlowPalPitchCorrection(): ?string
    {
        return $this->slowPalPitchCorrection;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->slowPalPitchCorrection) {
            if (!SlowPalPitchCorrection::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "slowPalPitchCorrection" for "%s". The value "%s" is not a valid "SlowPalPitchCorrection".', __CLASS__, $v));
            }
            $payload['slowPalPitchCorrection'] = $v;
        }

        return $payload;
    }
}
