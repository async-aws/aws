<?php

namespace AsyncAws\Translate\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Translate\Enum\Formality;
use AsyncAws\Translate\Enum\Profanity;

/**
 * Settings to configure your translation output, including the option to mask profane words and phrases.
 */
final class TranslationSettings
{
    private $formality;

    /**
     * Enable the profanity setting if you want Amazon Translate to mask profane words and phrases in your translation
     * output.
     */
    private $profanity;

    /**
     * @param array{
     *   Formality?: null|Formality::*,
     *   Profanity?: null|Profanity::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->formality = $input['Formality'] ?? null;
        $this->profanity = $input['Profanity'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Formality::*|null
     */
    public function getFormality(): ?string
    {
        return $this->formality;
    }

    /**
     * @return Profanity::*|null
     */
    public function getProfanity(): ?string
    {
        return $this->profanity;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->formality) {
            if (!Formality::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Formality" for "%s". The value "%s" is not a valid "Formality".', __CLASS__, $v));
            }
            $payload['Formality'] = $v;
        }
        if (null !== $v = $this->profanity) {
            if (!Profanity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Profanity" for "%s". The value "%s" is not a valid "Profanity".', __CLASS__, $v));
            }
            $payload['Profanity'] = $v;
        }

        return $payload;
    }
}
