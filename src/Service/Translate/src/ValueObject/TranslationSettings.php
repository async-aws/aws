<?php

namespace AsyncAws\Translate\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Translate\Enum\Formality;
use AsyncAws\Translate\Enum\Profanity;

/**
 * Settings to configure your translation output, including the option to set the formality level of the output text and
 * the option to mask profane words and phrases.
 */
final class TranslationSettings
{
    /**
     * You can optionally specify the desired level of formality for translations to supported target languages. The
     * formality setting controls the level of formal language usage (also known as register [^1]) in the translation
     * output. You can set the value to informal or formal. If you don't specify a value for formality, or if the target
     * language doesn't support formality, the translation will ignore the formality setting.
     *
     * If you specify multiple target languages for the job, translate ignores the formality setting for any unsupported
     * target language.
     *
     * For a list of target languages that support formality, see Supported languages [^2] in the Amazon Translate Developer
     * Guide.
     *
     * [^1]: https://en.wikipedia.org/wiki/Register_(sociolinguistics)
     * [^2]: https://docs.aws.amazon.com/translate/latest/dg/customizing-translations-formality.html#customizing-translations-formality-languages
     */
    private $formality;

    /**
     * Enable the profanity setting if you want Amazon Translate to mask profane words and phrases in your translation
     * output.
     *
     * To mask profane words and phrases, Amazon Translate replaces them with the grawlix string “?$#@$“. This
     * 5-character sequence is used for each profane word or phrase, regardless of the length or number of words.
     *
     * Amazon Translate doesn't detect profanity in all of its supported languages. For languages that don't support
     * profanity detection, see Unsupported languages [^1] in the Amazon Translate Developer Guide.
     *
     * If you specify multiple target languages for the job, all the target languages must support profanity masking. If any
     * of the target languages don't support profanity masking, the translation job won't mask profanity for any target
     * language.
     *
     * [^1]: https://docs.aws.amazon.com/translate/latest/dg/customizing-translations-profanity.html#customizing-translations-profanity-languages
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

    /**
     * @param array{
     *   Formality?: null|Formality::*,
     *   Profanity?: null|Profanity::*,
     * }|TranslationSettings $input
     */
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
