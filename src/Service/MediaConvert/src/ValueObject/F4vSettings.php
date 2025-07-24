<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\F4vMoovPlacement;

/**
 * Settings for F4v container.
 */
final class F4vSettings
{
    /**
     * To place the MOOV atom at the beginning of your output, which is useful for progressive downloading: Leave blank or
     * choose Progressive download. To place the MOOV at the end of your output: Choose Normal.
     *
     * @var F4vMoovPlacement::*|string|null
     */
    private $moovPlacement;

    /**
     * @param array{
     *   MoovPlacement?: null|F4vMoovPlacement::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->moovPlacement = $input['MoovPlacement'] ?? null;
    }

    /**
     * @param array{
     *   MoovPlacement?: null|F4vMoovPlacement::*|string,
     * }|F4vSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return F4vMoovPlacement::*|string|null
     */
    public function getMoovPlacement(): ?string
    {
        return $this->moovPlacement;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->moovPlacement) {
            if (!F4vMoovPlacement::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "moovPlacement" for "%s". The value "%s" is not a valid "F4vMoovPlacement".', __CLASS__, $v));
            }
            $payload['moovPlacement'] = $v;
        }

        return $payload;
    }
}
