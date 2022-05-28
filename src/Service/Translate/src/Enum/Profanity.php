<?php

namespace AsyncAws\Translate\Enum;

/**
 * Enable the profanity setting if you want Amazon Translate to mask profane words and phrases in your translation
 * output.
 * To mask profane words and phrases, Amazon Translate replaces them with the grawlix string “?$#@$“. This
 * 5-character sequence is used for each profane word or phrase, regardless of the length or number of words.
 * Amazon Translate does not detect profanity in all of its supported languages. For languages that support profanity
 * detection, see Supported Languages and Language Codes in the Amazon Translate Developer Guide.
 *
 * @see https://docs.aws.amazon.com/translate/latest/dg/what-is.html#what-is-languages
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
