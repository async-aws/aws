<?php

namespace AsyncAws\Translate\Enum;

/**
 * Enable the profanity setting if you want Amazon Translate to mask profane words and phrases in your translation
 * output.
 * To mask profane words and phrases, Amazon Translate replaces them with the grawlix string “?$#@$“. This
 * 5-character sequence is used for each profane word or phrase, regardless of the length or number of words.
 * Amazon Translate doesn't detect profanity in all of its supported languages. For languages that don't support
 * profanity detection, see Unsupported languages in the Amazon Translate Developer Guide.
 * If you specify multiple target languages for the job, all the target languages must support profanity masking. If any
 * of the target languages don't support profanity masking, the translation job won't mask profanity for any target
 * language.
 *
 * @see https://docs.aws.amazon.com/translate/latest/dg/customizing-translations-profanity.html#customizing-translations-profanity-languages
 */
final class Profanity
{
    public const MASK = 'MASK';

    public static function exists(string $value): bool
    {
        return isset([
            self::MASK => true,
        ][$value]);
    }
}
